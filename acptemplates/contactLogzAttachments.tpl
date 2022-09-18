<section class="section">
	<h2 class="sectionTitle">{lang}wcf.acp.contact.logz.attachments.sender{/lang}</h2>
	<ul class="containerList">
		{foreach from=$attachments item=temp}
			{if $temp['object']}
				{assign var='attachment' value=$temp['object']}
				<li>
					<div class="box64">
						<a href="{link controller='Attachment' id=$attachment->attachmentID}{/link}"{if $attachment->isImage} class="jsImageViewer" title="{$attachment->filename}"{/if}>
							{if $attachment->tinyThumbnailType}
								<img src="{link controller='Attachment' id=$attachment->attachmentID}tiny=1{/link}" class="attachmentTinyThumbnail" alt="">
							{else}
								<span class="icon icon64 fa-{@$attachment->getIconName()}"></span>
							{/if}
						</a>
						
						<div>
							<p><a href="{link controller='Attachment' id=$attachment->attachmentID}{/link}">{$attachment->filename|tableWordwrap}</a></p>
							<p><small>{if $attachment->userID}{if $__wcf->session->getPermission('admin.user.canEditUser')}<a href="{link controller='UserEdit' id=$attachment->userID}{/link}">{$attachment->username}</a>{else}{$attachment->username}{/if}{else}{lang}wcf.user.guest{/lang}{/if}</small></p>
						</div>
					</div>
				</li>
			{else}
				<li><span class="icon icon64 fa-{@$contact->getIconName($temp['filename'])}"></span>{$temp['filename']}</li>
			{/if}
		{/foreach}
	</ul>
</section>