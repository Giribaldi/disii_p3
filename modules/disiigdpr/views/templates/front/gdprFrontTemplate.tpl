{capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
        {l s='My account'}
    </a>
    <span class="navigation-pipe">
		{$navigationPipe}
	</span>
    <span class="navigation_page">
		Vie privée
	</span>
{/capture}

<div class="box">
    <h1 class="page-subheading">
        GDPR Agreement
    </h1>
        <form method="post">
            {foreach from=$data_files item=data_file}
                <div class="form-group ">
                    <label class="control-label ">
                        {$data_file.name}
                    </label>
                    <p>Description : {$data_file.description}</p>
                    <label class="radio-inline">
                        <input type="radio" name="{$data_file.id_datafiles}" id="inlineRadio1" {if isset($agreements[$data_file.id_datafiles]) && $agreements[$data_file.id_datafiles] eq 1}checked="checked" {/if}value="1"> Accept
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="{$data_file.id_datafiles}" id="inlineRadio2" {if isset($agreements[$data_file.id_datafiles]) && $agreements[$data_file.id_datafiles] eq 0}checked="checked" {/if}value="0"> Refuse
                    </label>
                </div>
            {/foreach}
            <div class="form-group">
                <div>
                    <button class="btn btn-primary " name="submit_gdpr_agreement" type="submit">
                        Submit
                    </button>
                </div>
            </div>

        </form>
</div>


<div class="box">
    <h1 class="page-subheading">
        Données enregistrées
    </h1>
    <div class="panel panel-info">
        <div class="panel-heading">Abandoned Carts</div>
        <div class="panel-body">
            {$lost_basket}
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Orders</div>
        <div class="panel-body">
            {$orders}
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Visits</div>
        <div class="panel-body">
            {$visits}
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Adresses</div>
        <div class="panel-body">
            {$addresses}
        </div>
    </div>


</div>
<div></div>
<ul class="footer_links clearfix">
    <li>
        <a class="btn btn-default button button-small" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
			<span>
				<i class="icon-chevron-left"></i> {l s='Back to your account'}
			</span>
        </a>
    </li>
</ul>
