var imageBulletTemplates = {
    row: $('#image_bullet_base'),
    bullet: $('#image_bullet_bullet'),
}

function addImageBulletRow(id, image = null, title = null, bullets = [], active = true) {
    var imageBulletEditor = $('#bullet-editor');
    imageBulletEditor.append(
        $("#image_bullet_base")
            .html()
            .replaceAll("%IMG%", image)
            .replaceAll("%TITLE%", title)
            .replaceAll("%ID%", id)
    )
    var img = imageBulletEditor.find(".row[data-bullet-id='" + id + "'] img");

    img.on('click', function (e) {
        var left = e.offsetX / img.width() * 100;
        var top = e.offsetY / img.height() * 100;

        addImageBulletBulletText(id, left, top, "", "");
    })

    if(bullets !== null) {
        bullets.forEach(
            v => {
                addImageBulletBulletText(id, v.left, v.top, v.text, v.link ?? "")
            }
        )
    }
}

function randomBulletId() {
    return Math.floor(Math.random() * 10000);
}

function addImageBulletBulletText(id, left, top, text, link) {
    var parent = $(".row[data-bullet-id='" + id + "']");
    var list = parent.find('.bullet-list');
    var image = parent.find('.image-bullet');
    var unique_id = randomBulletId();

    list.append(
        $("#image_bullet_bullet")
            .html()
            .replaceAll("%TEXT%", text)
            .replaceAll("%LINK%", link)
            .replaceAll("%LEFT%", left)
            .replaceAll("%TOP%", top)
            .replaceAll("%LEFT_ROUNDED%", Math.round(left))
            .replaceAll("%TOP_ROUNDED%", Math.round(top))
            .replaceAll("%ID%", unique_id)
    )


    $('<div></div>', {
        class: "bullet",
        style: "--top: " +  top + "%;--left: " + left + "%",
        "data-unique": unique_id
    }).appendTo(image)
}

$(document).on('change', '#add_image_bullet', function (e) {
    var formData = new FormData();
    formData.append('image_bullet', $('#add_image_bullet')[0].files[0]);

    $.ajax({
        url : $('#add-bullet-url').val(),
        type : 'POST',
        data : formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success : function(data) {
            if(data.success) {
                addImageBulletRow(data.id, data.image);
                $.growl.notice({ message: "Image créée" });
            }
            else {
                $.growl.error({ message: "Une erreur est survenue" });
            }
        }
    });
})


$(document).on('click', 'button[data-remove-point]', function (e) {
    var id = $(this).attr('data-remove-point');

    $('.bullet-row-data[data-unique="' + id + '"]').remove();
    $('.bullet[data-unique="' + id + '"]').remove();
})

$(document).on('click', 'button[data-delete-image-bullet]', function (e) {
    var id = $(this).attr('data-delete-image-bullet');


    $.ajax({
        url : $('#remove-bullet-url').val().replace("ID_BULLET", id),
        type : 'POST',
        data : {},
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success : function(data) {
            if(data.success) {
                $(".row[data-bullet-id='" + id + "']").remove();
                $.growl.notice({ message: "Image supprimée" });
            }
            else {
                $.growl.error({ message: "Une erreur est survenue" });
            }
        }
    });
})


$(document).on('click', 'button[data-save-image-bullet]', function (e) {
    var id = $(this).attr('data-save-image-bullet');

    var bullets = [];

    $('.row[data-bullet-id="' + id + '"] .bullet-row-data').each(function(v) {
        bullets.push(
            {
                top: this.getAttribute('data-top'),
                left: this.getAttribute('data-left'),
                text: this.querySelector('textarea.text').value,
                link: this.querySelector('input.link').value,
            }
        )
    })

    $.ajax({
        url : $('#save-bullet-url').val().replace("ID_BULLET", id),
        type : 'POST',
        data : {
            title:   $('.row[data-bullet-id="' + id + '"] input.title').val(),
            bullets: JSON.stringify(bullets)
        },
        success : function(data) {
            if(data.success) {
                $.growl.notice({ message: "Image sauvegardée" });
            }
            else {
                $.growl.error({ message: "Une erreur est survenue" });
            }
        }
    });
})


$(document).on('mouseenter', '.bullet-row-data, .bullet', function (e) {
    var id = $(this).attr('data-unique');

    $('.bullet-row-data[data-unique="' +  id +'"]').addClass('active');
    $('.bullet[data-unique="' +  id +'"]').addClass('active');

    $('.bullet-row-data:not([data-unique="' +  id +'"])').removeClass('active');
    $('.bullet:not([data-unique="' +  id +'"])').removeClass('active');
});

$(document).ready(function (e) {
    let value = $('#bullets').text();
    value = JSON.parse(value);

    value.forEach(
        v => {
            addImageBulletRow(v.id, v.image, v.title ?? "", v.bullets);
        }
    )
    console.log(value);
});