<h2>Images</h2>


<div class="row">
    {foreach from=$bullets item=bullet}
        <div class="col-md-6 mb-3">
            {if $bullet->title}
                <h3 class="">{$bullet->title}</h3>
            {/if}
            <div class="image-bullet">
                <img src="/img/image_bullet/{$bullet->image}" alt="{$bullet->title}"/>
                {foreach from=$bullet->decodedBullets() item=item}
                    {if !empty($item.link) && $item.link !== ""}
                        <a target="_blank" href="{$item.link}" class="bullet bullet-with_link" data-toggle="tooltip"  title="{$item.text}" style="--left: {$item.left}%; --top: {$item.top}%"></a>
                    {else}
                        <div class="bullet" data-toggle="tooltip"  title="{$item.text}" style="--left: {$item.left}%; --top: {$item.top}%"></div>
                    {/if}
                {/foreach}
            </div>
        </div>
    {/foreach}
</div>