<?php

namespace FluxBB\Markdown\Extension\Core;

use FluxBB\Markdown\Common\Collection;
use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Exception\SyntaxError;
use FluxBB\Markdown\Extension\ExtensionInterface;
use FluxBB\Markdown\Renderer\RendererAwareInterface;
use FluxBB\Markdown\Renderer\RendererAwareTrait;
use FluxBB\Markdown\Markdown;

/**
 * Original source code from Markdown.pl
 *
 * > Copyright (c) 2004 John Gruber
 * > <http://daringfireball.net/projects/markdown/>
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class LinkExtension implements ExtensionInterface, RendererAwareInterface
{

    use RendererAwareTrait;

    /**
     * @var Markdown
     */
    private $markdown;

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $this->markdown = $markdown;

        $markdown->on('initialize', array($this, 'initialize'), 30);
        $markdown->on('inline', array($this, 'processReferencedLink'), 40);
        $markdown->on('inline', array($this, 'processInlineLink'), 40);
        $markdown->on('inline', array($this, 'processAutoLink'), 50);
    }

    /**
     * Strips link definitions from text, stores the URLs and titles in hash references.
     *
     * @param Text  $text
     * @param array $options
     */
    public function initialize(Text $text, array $options = array())
    {
        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            ^[ ]{0,' . $options['tabWidth'] . '}\[(.+)\]:   # id = $1
              [ \t]*
              \n?               # maybe *one* newline
              [ \t]*
            <?(\S+?)>?          # url = $2
              [ \t]*
              \n?               # maybe one newline
              [ \t]*
            (?:
                (?<=\s)         # lookbehind for whitespace
                ["\'(]
                (.+?)           # title = $3
                ["\')]
                [ \t]*
            )?  # title is optional
            (?:\n+|\Z)
        }xm', function (Text $whole, Text $id, Text $url, Text $title = null) {
            $id->lower();
            $this->markdown->emit('escape.special_chars', [$url->replace('/(?<!\\\\)_/', '\\\\_')]);
            $this->markdown->getUrlRegistry()->set($id, htmlspecialchars($url, ENT_QUOTES, 'UTF-8', false));

            if ($title) {
                $this->markdown->getTitleRegistry()->set($id, preg_replace('/"/', '&quot;', $title));
            }

            return '';
        });
    }

    /**
     * Handle reference-style links: [link text] [id]
     *
     * @param Text  $text
     * @param array $options
     */
    public function processReferencedLink(Text $text, array $options = array())
    {
        if (!$text->contains('[')) {
            return;
        }

        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            #(                   # wrap whole match in $1
              \[
                (' . $this->getNestedBrackets() . ')    # link text = $2
              \]

              [ ]?              # one optional space
              (?:\n[ ]*)?       # one optional newline followed by spaces

              \[
                (.*?)       # id = $3
              \]
            #)
        }xs', function (Text $whole, Text $linkText, Text $id = null) use ($options) {
            if (is_null($id) || (string) $id == '') {
                $id = new Text($linkText);
            }

            $id->lower();

            if ($this->markdown->getUrlRegistry()->exists($id)) {
                $url = new Text($this->markdown->getUrlRegistry()->get($id));
                $url->escapeHtml()->replace('/(?<!\\\\)_/', '\\\\_');
                $this->markdown->emit('escape.special_chars', [$url]);

                $linkOptions = [
                    'href' => $url->getString(),
                ];

                if ($this->markdown->getTitleRegistry()->exists($id)) {
                    $title = new Text($this->markdown->getTitleRegistry()->get($id));
                    $linkOptions['title'] = $title->escapeHtml()->getString();
                }

                return $this->getRenderer()->renderLink($linkText->getString(), $linkOptions);
            } else {
                if ($options['strict']) {
                    throw new SyntaxError(
                        sprintf('Unable to find id "%s" in Reference-style link', $id),
                        $this, $whole, $this->markdown
                    );
                }

                return $whole;
            }
        });
    }

    /**
     * Inline-style links: [link text](url "optional title")
     *
     * @param Text $text
     */
    public function processInlineLink(Text $text)
    {
        if (!$text->contains('[')) {
            return;
        }

        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            #(               # wrap whole match in $1
              \[
                (' . $this->getNestedBrackets() . ')    # link text = $2
              \]
              \(            # literal paren
                [ \t]*
                <?(.*?)>?   # href = $3
                [ \t]*
                (           # $4
                  ([\'"])   # quote char = $5
                  (.*?)     # Title = $6
                  \4        # matching quote
                )?          # title is optional
              \)
            #)
        }xs', function (Text $whole, Text $linkText, Text $url, Text $a = null, Text $q = null, Text $title = null) {
            $url->escapeHtml()->replace('/(?<!\\\\)_/', '\\\\_');
            $this->markdown->emit('escape.special_chars', [$url]);

            $linkOptions = [
                'href' => $url->getString(),
            ];

            if ($title) {
                $linkOptions['title'] = $title->replace('/"/', '&quot;')->escapeHtml()->getString();
            }

            return $this->getRenderer()->renderLink($linkText, $linkOptions);
        });
    }

    /**
     * Make links out of things like `<http://example.com/>`
     *
     * @param Text $text
     */
    public function processAutoLink(Text $text)
    {
        if (!$text->contains('<')) {
            return;
        }

        $protocols = implode('|', $this->getValidProtocols());
        $text->replace('{<((?:'.$protocols.'):[^\'">\s]+)>}', function (Text $w, Text $url) {
            $this->markdown->emit('escape.special_chars', [$url->replace('/(?<!\\\\)_/', '\\\\_')]);

            return $this->getRenderer()->renderLink($url, [
                'href' => $url->getString()
            ]);
        });

        /** @noinspection PhpUnusedParameterInspection */
        $text->replace('{
            <
            (?:mailto:)?
            (
                [a-zA-Z0-9.!#$%&\'*+/=?^_`{|}~-]+
                \@
                [a-zA-Z0-9]
                (?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?
                (?:\\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*
            )
            >
        }ix', function (Text $w, Text $address) {
            return $this->getRenderer()->renderLink($address, ['href' => 'mailto:' . $address]);
        });
    }
    
    protected function getValidProtocols()
    {
        return [
            'coap', 'doi', 'javascript', 'aaa', 'aaas', 'about', 'acap', 'cap',
            'cid', 'crid', 'data', 'dav', 'dict', 'dns', 'file', 'ftp', 'geo', 'go',
            'gopher', 'h323', 'http', 'https', 'iax', 'icap', 'im', 'imap', 'info',
            'ipp', 'iris', 'iris.beep', 'iris.xpc', 'iris.xpcs', 'iris.lwz', 'ldap',
            'mailto', 'mid', 'msrp', 'msrps', 'mtqp', 'mupdate', 'news', 'nfs',
            'ni', 'nih', 'nntp', 'opaquelocktoken', 'pop', 'pres', 'rtsp',
            'service', 'session', 'shttp', 'sieve', 'sip', 'sips', 'sms', 'snmp',
            'soap.beep', 'soap.beeps', 'tag', 'tel', 'telnet', 'tftp', 'thismessage',
            'tn3270', 'tip', 'tv', 'urn', 'vemmi', 'ws', 'wss', 'xcon',
            'xcon-userid', 'xmlrpc.beep', 'xmlrpc.beeps', 'xmpp', 'z39.50r',
            'z39.50s', 'adiumxtra', 'afp', 'afs', 'aim', 'apt',' attachment', 'aw',
            'beshare', 'bitcoin', 'bolo', 'callto', 'chrome',' chrome-extension',
            'com-eventbrite-attendee', 'content', 'cvs',' dlna-playsingle',
            'dlna-playcontainer', 'dtn', 'dvb', 'ed2k', 'facetime', 'feed',
            'finger', 'fish', 'gg', 'git', 'gizmoproject', 'gtalk', 'hcp', 'icon',
            'ipn', 'irc', 'irc6', 'ircs', 'itms', 'jar', 'jms', 'keyparc', 'lastfm',
            'ldaps', 'magnet', 'maps', 'market',' message', 'mms', 'ms-help',
            'msnim', 'mumble', 'mvn', 'notes', 'oid', 'palm', 'paparazzi',
            'platform', 'proxy', 'psyc', 'query', 'res', 'resource', 'rmi', 'rsync',
            'rtmp', 'secondlife', 'sftp', 'sgn', 'skype', 'smb', 'soldat',
            'spotify', 'ssh', 'steam', 'svn', 'teamspeak', 'things', 'udp',
            'unreal', 'ut2004', 'ventrilo', 'view-source', 'webcal', 'wtai',
            'wyciwyg', 'xfire', 'xri', 'ymsgr',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'link';
    }

    /**
     * @return string
     */
    protected function getNestedBrackets()
    {
        return str_repeat('(?>[^\[\]]+|\[', 7) . str_repeat('\])*', 7);
    }

}
