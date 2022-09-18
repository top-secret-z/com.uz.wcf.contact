{if $mimeType === 'text/plain'}
{capture assign='content'}{lang}wcf.contact.mail.copy.plaintext{/lang}{/capture}
{include file='email_plaintext'}
{else}
    {capture assign='content'}
    {lang}wcf.contact.mail.copy.html{/lang}
    {/capture}
    {include file='email_html'}
{/if}
