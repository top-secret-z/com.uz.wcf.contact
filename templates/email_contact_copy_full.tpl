{if $mimeType === 'text/plain'}
{capture assign='content'}{lang}wcf.contact.mail.copy.plaintext.full{/lang}{/capture}
{include file='email_plaintext'}
{else}
	{capture assign='content'}
	{lang}wcf.contact.mail.copy.html.full{/lang}
	{/capture}
	{include file='email_html'}
{/if}
