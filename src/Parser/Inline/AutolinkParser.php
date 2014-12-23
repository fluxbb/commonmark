<?php

namespace FluxBB\Markdown\Parser\Inline;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\InlineNodeAcceptorInterface;
use FluxBB\Markdown\Node\Link;
use FluxBB\Markdown\Parser\AbstractInlineParser;

class AutolinkParser extends AbstractInlineParser
{

    /**
     * Parse the given content.
     *
     * Any newly created nodes should be appended to the given target. Any remaining content should be passed to the
     * next parser in the chain.
     *
     * @param Text $content
     * @param InlineNodeAcceptorInterface $target
     * @return void
     */
    public function parseInline(Text $content, InlineNodeAcceptorInterface $target)
    {
        if ($content->contains('<')) {
            $this->parseAutolink($content, $target);
        } else {
            $this->next->parseInline($content, $target);
        }
    }

    protected function parseAutolink(Text $content, InlineNodeAcceptorInterface $target)
    {
        $protocols = implode('|', $this->getValidProtocols());

        $content->handle(
            '{<((?:'.$protocols.'):[^<>\s]*)>}i',
            function (Text $w, Text $url) use ($target) {
                $target->addInline(new Link($url, $url->copy()));
            },
            function (Text $part) use ($target) {
                $this->next->parseInline($part, $target);
            }
        );
    }

    /**
     * @return string[]
     */
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

}