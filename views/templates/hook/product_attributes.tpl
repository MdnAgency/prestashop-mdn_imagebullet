<div class="m-b-1 m-t-1">
    <h2>{l s='Image bullets' mod='mdn_imagebullet'}</h2>
    <input type="hidden" id="add-bullet-url" value="{$add_url}" >
    <input type="hidden" id="remove-bullet-url" value="{$remove_url}" >
    <input type="hidden" id="save-bullet-url" value="{$save_url}" >
    <script id="bullets" type="text/template">{$bullets}</script>
    <label for="add_image_bullet">
        <input type="file" id="add_image_bullet" >
        {* <button type="button" class="btn btn-outline-primary sensitive add"><i class="material-icons">add_circle</i> Ajouter une image bullet</button>*}
    </label>

    <div id="bullet-editor">

    </div>

    <script id="image_bullet_base" type="text/template">
        <div class="row" data-bullet-id="%ID%">
            <div class="col-6">
                <div class="sticky-bullet">
                    <input placeholder="Titre de l'image" class=" mb-2 form-control title" value="%TITLE%">
                    <div class="image-bullet">
                        <img src="/img/image_bullet/%IMG%"/>
                    </div>
                    <button data-delete-image-bullet="%ID%" type="button" class="mt-2 btn btn-outline-danger sensitive remove">Supprimer l'image</button>
                    <button data-save-image-bullet="%ID%" type="button" class="mt-2 btn btn-outline-success sensitive save">Enregistrer</button>
                </div>
            </div>
            <div class="col-6">
                <strong>{l s='Click on image to add bullets, and save it after.' mod='mdn_imagebullet'}</strong>
                <div class="bullet-list">

                </div>
            </div>
        </div>
    </script>
    <script id="image_bullet_bullet" type="text/template">
        <div class="row bullet-row-data" data-unique="%ID%" data-top="%TOP%" data-left="%LEFT%">
            <div class="col-12">
                <div class="row mt-2 mb-2">
                    <div class="col-6  ">
                        <strong>x: %LEFT_ROUNDED%; y: %TOP_ROUNDED%</strong>
                    </div>
                    <div class="text-right col-6">
                        <button type="button" data-remove-point="%ID%" class="btn btn-outline-danger btn-sm sensitive remove">Supprimer le point</button>
                    </div>
                </div>
                <textarea  class="text form-control">%TEXT%</textarea>
                <input placeholder="URL à ouvrir au clique sur la puce" class="link mt-2 mb-2 form-control" value="%LINK%">
            </div>
        </div>
    </script>
</div>
<hr/>
