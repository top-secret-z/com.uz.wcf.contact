{if CONTACT_FORM_COPY==='optional'}
    <dl>
        <dt><label for="sendCopy">{lang}wcf.contact.send.copy{if !ENABLE_ENTERPRISE_MODE && CONTACT_FORM_COPY_FULL}.full{/if}{/lang}</label></dt>
        <dd>
            <label><input type="checkbox" id="sendCopy" name="sendCopy" value="1"{if $sendCopy} checked{/if}> {lang}wcf.contact.send.copy.activate{/lang}</label>
        </dd>
    </dl>
{/if}

{if CONTACT_FORM_COPY==='on'}
    <dl>
        <dt><label for="sendCopy">{lang}wcf.contact.send.copy{if !ENABLE_ENTERPRISE_MODE && CONTACT_FORM_COPY_FULL}.full{/if}{/lang}</label></dt>
        <dd>
            <label><input type="checkbox" id="sendCopy" name="sendCopy" value="1" checked disabled> {lang}wcf.contact.send.copy.activate{/lang}</label>
        </dd>
    </dl>
{/if}
