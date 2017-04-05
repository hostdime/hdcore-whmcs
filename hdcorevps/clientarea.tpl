<style type="text/css">
    .module-client-area .row {
        padding-bottom: 15px;
    }
</style>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>ID</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps.cuid}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Memory</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps.memory}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Operating System</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps.os}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Control Panel</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps.control_panel}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Main IP</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps.ip_main}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>DNS</strong>
    </div>
    <div class="col-sm-7 text-left">
        {foreach from=$vps.dns item=nameserver}
            {$nameserver.dns} {$nameserver.ip}<br />
        {/foreach}
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>SSH Port</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps.ssh_port}
        <a href="ssh://{$vps.ip_main}:{$vps.ssh_port}" class="btn btn-primary">
        <i class="fa fa-terminal"></i>
        SSH to Server
        </a>
    </div>
</div>
<div class="row">
    <div class="col-sm-5 text-right">
        <strong>Disk Usage</strong>
    </div>
    <div class="col-sm-7 text-left">
        {$vps.disk_usage} MB / {$vps.disk_quota} MB
    </div>
</div>

{if $ip_addresses}
<h3 class="pull-left">Reverse Dns Records</h3>
<div class="row">
    <form method="clientarea.php?action=productdetails" class="form-horizontal">
        <input type="hidden" name="id" value="{$vps.cuid}" />
        <input type="hidden" name="a" value="rdns" />
        <input type="hidden" name="modop" value="custom" />
        <table class="table table-stripeds">
            <thead>
                <tr>
                    <th width="35%">IP Address</th>
                    <th width="65%">PTR Record</th>
                </tr>
            </thead>
            {foreach from=$ip_addresses item=ip}
            <tr>
                <td>
                    {$ip.ip}
                </td>
                <td>
                    <input type="text" class="form-control" size="50" name="ptr[{$ip.ip}]" value="{$ip.ptr}" />
                </td>
            </tr>
            {/foreach}
            </tbody>
        </table>
        <button type="submit" class="btn btn-success pull-left">
            Save RDNS Records
        </button>
    </form>
</div>
<br />
{/if}

{if $day_graph}
<div class="row">
    <h4>Daily Graph</h4>
    <img src="data:image/png;base64,{$day_graph}" />
</div>
<div class="row">
    <h4>Weekly Graph</h4>
    <img src="data:image/png;base64,{$week_graph}" />
</div>
<div class="row">
    <h4>Monthly Graph</h4>
    <img src="data:image/png;base64,{$month_graph}" />
</div>
<div class="row">
    <h4>Yearly Graph</h4>
    <img src="data:image/png;base64,{$year_graph}" />
</div>
{/if}
