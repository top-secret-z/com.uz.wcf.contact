{include file='header' pageTitle='wcf.acp.contact.logz.list'}

<script data-relocate="true" src="{@$__wcf->getPath()}js/UZCONTACT.js?v={@LAST_UPDATE_TIME}"></script>
<script data-relocate="true">
	$(function() {
		WCF.Language.addObject({
			'wcf.acp.contact.logz.inWork': 		'{jslang}wcf.acp.contact.logz.inWork{/jslang}',
			'wcf.acp.contact.logz.notInWork': 	'{jslang}wcf.acp.contact.logz.notInWork{/jslang}'
		});
		
		new WCF.Action.Delete('wcf\\data\\contact\\logz\\ContactLogzAction', '.jsContactLogRow');
		new UZCONTACT.UzContact('wcf\\data\\contact\\logz\\ContactLogzAction', '.jsContactLogRow');
	});
</script>

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.contact.logz.list{/lang}{if $items} <span class="badge badgeInverse">{#$items}</span>{/if}</h1>
	</div>
	
	{hascontent}
		<nav class="contentHeaderNavigation">
			<ul>
				{content}
					{if $objects|count}
						<li><a title="{lang}wcf.acp.contact.logz.clear{/lang}" class="button jsContactLogClear"><span class="icon icon16 fa-times"></span> <span>{lang}wcf.acp.contact.logz.clear{/lang}</span></a></li>
					{/if}
					
					{event name='contentHeaderNavigation'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</header>

<form method="post" action="{link controller='ContactLogzList'}{/link}">
	<section class="section">
		<h2 class="sectionTitle">{lang}wcf.global.filter{/lang}</h2>
		
		<div class="row rowColGap formGrid">
			<dl class="col-xs-12 col-md-4">
				<dt></dt>
				<dd>
					<input type="text" id="sender" name="sender" value="{$sender}" placeholder="{lang}wcf.acp.contact.logz.sender{/lang}" class="long">
				</dd>
			</dl>
			
			<dl class="col-xs-12 col-md-4">
				<dt></dt>
				<dd>
					<input type="text" id="email" name="email" value="{$email}" placeholder="{lang}wcf.acp.contact.logz.email{/lang}" class="long">
				</dd>
			</dl>
			
			<dl class="col-xs-12 col-md-4">
				<dt></dt>
				<dd>
					<input type="text" id="receivername" name="receivername" value="{$receivername}" placeholder="{lang}wcf.acp.contact.logz.receivername{/lang}" class="long">
				</dd>
			</dl>
			
			<dl class="col-xs-12 col-md-4">
				<dt></dt>
				<dd>
					<select name="inWork" id="inWork">
						<option value="-1"{if $inWork == -1} selected{/if}>{lang}wcf.acp.contact.logz.status{/lang}</option>
						<option value="1"{if $inWork == 1} selected{/if}>{lang}wcf.acp.contact.logz.inWork{/lang}</option>
						<option value="0"{if $inWork == 0} selected{/if}>{lang}wcf.acp.contact.logz.notInWork{/lang}</option>
						
					</select>
				</dd>
			</dl>
			
			{event name='filterFields'}
		</div>
		
		<div class="formSubmit">
			<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
			{csrfToken}
		</div>
	</section>
</form>

{hascontent}
	<div class="paginationTop">
		{content}
			{assign var='linkParameters' value=''}
			{if $email}{capture append=linkParameters}&email={@$email|rawurlencode}{/capture}{/if}
			{if $inWork != -1}{capture append=linkParameters}&inWork={@$inWork}{/capture}{/if}
			{if $receivername}{capture append=linkParameters}&receivername={@$receivername|rawurlencode}{/capture}{/if}
			{if $sender}{capture append=linkParameters}&sender={@$sender|rawurlencode}{/capture}{/if}
			
			{pages print=true assign=pagesLinks controller="ContactLogzList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder$linkParameters"}
		{/content}
		
	</div>
{/hascontent}

{if $objects|count}
	<div class="section tabularBox">
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnContactLogzID{if $sortField == 'contactLogzID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='ContactLogzList'}pageNo={@$pageNo}&sortField=contactLogzID&sortOrder={if $sortField == 'contactLogzID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
					<th class="columnText columnTime{if $sortField == 'time'} active {@$sortOrder}{/if}"><a href="{link controller='ContactLogzList'}pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.contact.logz.time{/lang}</a></th>
					<th class="columnText columnStatus{if $sortField == 'inWork'} active {@$sortOrder}{/if}"><a href="{link controller='ContactLogzList'}pageNo={@$pageNo}&sortField=inWork&sortOrder={if $sortField == 'inWork' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.contact.logz.status{/lang}</a></th>
					{if LOG_IP_ADDRESS && $__wcf->session->getPermission('admin.user.canViewIpAddress')}
						<th class="columnText columnIpAddress{if $sortField == 'ipAddress'} active {@$sortOrder}{/if}"><a href="{link controller='ContactLogzList'}pageNo={@$pageNo}&sortField=ipAddress&sortOrder={if $sortField == 'ipAddress' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.contact.logz.ipAddress{/lang}</a></th>
					{/if}
					<th class="columnText columnUsername{if $sortField == 'username'} active {@$sortOrder}{/if}"><a href="{link controller='ContactLogzList'}pageNo={@$pageNo}&sortField=username&sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.contact.logz.username{/lang}</a></th>
					<th class="columnText columnSender{if $sortField == 'sender'} active {@$sortOrder}{/if}"><a href="{link controller='ContactLogzList'}pageNo={@$pageNo}&sortField=sender&sortOrder={if $sortField == 'sender' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.contact.logz.sender{/lang}</a></th>
					<th class="columnText columnEmail{if $sortField == 'email'} active {@$sortOrder}{/if}"><a href="{link controller='ContactLogzList'}pageNo={@$pageNo}&sortField=email&sortOrder={if $sortField == 'email' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.contact.logz.email{/lang}</a></th>
					<th class="columnText columnReceivername{if $sortField == 'receivername'} active {@$sortOrder}{/if}"><a href="{link controller='ContactLogzList'}pageNo={@$pageNo}&sortField=receivername&sortOrder={if $sortField == 'receivername' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.contact.logz.receivername{/lang}</a></th>
					<th class="columnText columnAttachments{if $sortField == 'hasAttachments'} active {@$sortOrder}{/if}"><a href="{link controller='ContactLogzList'}pageNo={@$pageNo}&sortField=hasAttachments&sortOrder={if $sortField == 'hasAttachments' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.contact.logz.hasAttachments{/lang}</a></th>
					{foreach from=$options item=option}
						<th class="columnText columnOption{$option->optionID}{if $option->optionID > 2} options{/if}{if $option->optionID == 2} optionText{/if}">{lang}{$option->optionTitle}{/lang}</th>
					{/foreach}
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=contact}
					<tr class="jsContactLogRow">
						<td class="columnIcon">
							<span class="icon icon16 fa-times jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$contact->contactLogzID}" data-confirm-message="{lang}wcf.acp.contact.logz.delete.sure{/lang}"></span>
						</td>
						<td class="columnID columnContactLogzID">{@$contact->contactLogzID}</td>
						<td class="columnText columnTime">{@$contact->time|time}</td>
						<td class="columnIcon columnStatus">
							<span class="icon icon16 fa-{if $contact->inWork}check-{/if}square-o jsToggleButton jsTooltip pointer" title="{lang}wcf.acp.contact.logz.{if !$contact->inWork}inWork{else}notInWork{/if}{/lang}" data-object-id="{@$contact->contactLogzID}" data-disable-message="{lang}wcf.acp.contact.logz.notInWork{/lang}" data-enable-message="{lang}wcf.acp.contact.logz.inWork{/lang}"></span>
						</td>
						{if LOG_IP_ADDRESS && $__wcf->session->getPermission('admin.user.canViewIpAddress')}
							<td class="columnText columnIpAddress">{$contact->ipAddress}</td>
						{/if}
						{if $contact->userID}
							<td class="columnText columnUsername"><a href="{link controller='UserEdit' id=$contact->userID}{/link}">{lang}{$contact->username}{/lang}</a></td>
						{else}
							<td class="columnText columnUsername">{lang}{$contact->username}{/lang}</td>
						{/if}
						
						<td class="columnText columnSender">{$contact->sender}</td>
						<td class="columnText columnEmail">{$contact->email}</td>
						{if $contact->receiverID}
							<td class="columnText columnReceivername"><a href="{link controller='ContactRecipientEdit' id=$contact->receiverID}{/link}" class="jsTooltip" title="{$contact->receiverEmail}">{$contact->receivername}</a></td>
						{else}
							<td class="columnText columnReceivername"><span class="jsTooltip" title="{$contact->receiverEmail}">{$contact->receivername}</span></td>
						{/if}
						
						<td class="columnText columnAttachments">{#$contact->hasAttachments} {if $contact->hasAttachments}<span class="icon icon24 fa-paperclip jsShowAttachmentButton jsTooltip pointer" title="{lang}wcf.acp.contact.logz.hasAttachments.open{/lang}" data-object-id="{@$contact->contactLogzID}"></span>{/if}</td>
						
						{foreach from=$options item=option}
							{assign var='key' value=$option->optionID}
							<td class="columnText columnOption{$option->optionID}{if $option->optionID > 2} options{/if}{if $option->optionID == 2} optionText{/if}">{$contact->option[$key]}</td>
						{/foreach}
					
					</tr>
				{/foreach}
			</tbody>
		</table>
		
	</div>
	
	<footer class="contentFooter">
		{hascontent}
			<div class="paginationBottom">
				{content}{@$pagesLinks}{/content}
			</div>
		{/hascontent}
		
		{hascontent}
			<nav class="contentFooterNavigation">
				<ul>
					{content}
						{if $objects|count}
						{/if}
						
						{event name='contentFooterNavigation'}
					{/content}
				</ul>
			</nav>
		{/hascontent}
	</footer>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

<script data-relocate="true">
	require(['Language', 'UZ/Contact/Acp/LogClear'], function (Language, UzContactAcpLogClear) {
		Language.addObject({
			'wcf.acp.contact.logz.clear.confirm': '{jslang}wcf.acp.contact.logz.clear.confirm{/jslang}'
		});
		
		new UzContactAcpLogClear();
	});
</script>

<script data-relocate="true">
	require(['Language', 'UZ/Contact/Acp/ShowAttachment'], function(Language, UzContactAcpShowAttachment) {
		Language.addObject({
			'wcf.acp.contact.logz.attachments': '{jslang}wcf.acp.contact.logz.attachments{/jslang}'
		});
		new UzContactAcpShowAttachment();
	});
</script>

{include file='footer'}
