<div>
    <a id="back-to-top"></a>
</div>

<style>
    #back-to-top {
        display: inline-block;
        background-color: #9acd57;
        width: 50px;
        height: 50px;
        margin: 0px auto;
        text-align: center;
        text-decoration: none;
        border-radius: 6px;
        position: fixed;
        bottom: 10px;
        right: 10px;
        transition: background-color .3s, 
            opacity .5s, visibility .5s;
        opacity: 0;
        visibility: hidden;
        z-index: 1000;
    }
    #back-to-top::after {
        content: "\f077";
        font-family: FontAwesome;
        font-weight: normal;
        font-style: normal;
        font-size: 2em;
        line-height: 50px;
        color: #fff;
    }
    #back-to-top:hover {
        cursor: pointer;
        background-color: #265a8e;
    }
    #back-to-top:active {
        background-color: #555;
    }
    #back-to-top.show {
        opacity: 1;
        visibility: visible;
    }
</style>

<script>
    var btn = $('#back-to-top');
    $(window).scroll(function() {
    if ($(window).scrollTop() > 300) {
        btn.addClass('show');
    } else {
        btn.removeClass('show');
    }
    });

    btn.on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({scrollTop:0}, '300');
    });
</script>
