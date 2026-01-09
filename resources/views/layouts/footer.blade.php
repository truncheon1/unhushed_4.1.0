<?php
$path = $path ?? '';
?>
<footer class="footer text-white">
    <!-- PHONE -->
    <div class="row justify-content-center">
        <div class="phone footerPhone">
            <div class="col-12 p-3" style="text-align:center;">
                <a href="{{ url($path.'/news') }}"><button class="btn btn-secondary px-5 mp-3">SUBSCRIBE</button></a>
            </div>
            <div class="col-12 pb-3">
                CONNECT WITH US
                <hr class="divider pb-1"/>
                <a href="https://bsky.app/profile/unhushed.org" target="_blank"><i class="fa-brands fa-bluesky fa-xl"></i></a>
                    <!-- END Bluesky -->
                <a href="https://www.linkedin.com/company/unhushed" target="_blank"><i class="fa-brands fa-linkedin fa-xl"></i></a>
                    <!-- END linkedin --> 
                <a href="https://www.facebook.com/Unhushed/" target="_blank"><i class="fa-brands fa-square-facebook fa-xl"></i></a>
                    <!-- END facebook -->
                <a href="https://www.instagram.com/unhushed/" target="_blank"><i class="fa-brands fa-square-instagram fa-xl"></i></a>
                    <!-- END instagram -->
                <a href="https://www.threads.net/@unhushed" target="_blank"><i class="fa-brands fa-square-threads fa-xl"></i></a>
                    <!-- END linkedin -->
            </div>
            <div class="col-12 pb-1">
                NEED HELP?
                <hr class="divider" />
                <a href="{{ url($path.'/support') }}">Contact Us</a>
                <br/><a href="{{ url($path.'/impressum') }}">Contact Information</a>
                <br/><a href="{{ url($path.'/subscription-info') }}">FAQ</a>
            </div>
            <div class="col-12 pb-2">
                <br/>LEGAL
                <hr class="divider" />
                <a href="{{ url($path.'/privacy') }}">Privacy Policy</a>
                <br/><a href="{{ url($path.'/purchases') }}">Purchases and Cancellations</a>
                <br/><a href="{{ url($path.'/terms') }}">Terms and Conditions</a>
            </div>
            <div class="col-12 pb-2">
                <div class="col align-self-center" style="text-align:center;">
                    <p>© {{date('Y')}} UN|HUSHED</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-md-center">
        <!-- DESKTOP -->
        <div class="col-2 laptop">
            LEGAL
            <hr class="divider" />
            <a href="{{ url($path.'/privacy') }}">Privacy Policy</a>
            <br/><a href="{{ url($path.'/purchases') }}">Purchases and Cancellations</a>
            <br/><a href="{{ url($path.'/terms') }}">Terms & Conditions</a>
        </div>
        <div class="col-2 laptop">
            NEED HELP?
            <hr class="divider" />
            <a href="{{ url($path.'/support') }}">Contact Us</a>
            <br/><a href="{{ url($path.'/impressum') }}">Contact Information</a>
            <br/><a href="{{ url($path.'/subscription-info') }}">FAQ</a>
        </div>
        
        <div class="col-md-4 col-sm-12 laptop">
            <div class="col align-self-center" style="text-align:center;">
                <a href="{{ url($path.'/news') }}"><button class="btn btn-secondary px-5 m-3">SUBSCRIBE</button></a>
                <br>© {{date('Y')}} UN|HUSHED
            </div>
        </div>
        <div class="col-2 laptop">
        </div>
        <div class="col-md-2 col-sm-12 float-end laptop">
            CONNECT WITH US
            <hr class="divider pb-2"/>
            <a href="https://bsky.app/profile/unhushed.org" target="_blank"><i class="fa-brands fa-bluesky fa-2xl"></i></a>
                <!-- END Bluesky -->
            <a href="https://www.linkedin.com/company/unhushed" target="_blank"><i class="fa-brands fa-linkedin fa-2xl"></i></a>
                <!-- END linkedin -->
            <a href="https://www.facebook.com/Unhushed/" target="_blank"><i class="fa-brands fa-square-facebook fa-2xl"></i></a>
                <!-- END facebook -->
            <a href="https://www.instagram.com/unhushed/" target="_blank"><i class="fa-brands fa-square-instagram fa-2xl"></i></a>
                <!-- END instagram -->
            <a href="https://www.threads.net/@unhushed" target="_blank"><i class="fa-brands fa-square-threads fa-2xl"></i></a>
                <!-- END threads --> 
        </div>
    </div>
</footer>
<style>
    hr.divider { 
        margin: 2px;
        border-width: 1px;
        max-width: 150px;
    }
    .footerPhone{
        /* Make footer participate in document flow to avoid overlapping content */
        position: relative;
        background: rgb(76,113,157);
        background: linear-gradient(0deg, rgba(76,113,157,1) 0%, rgba(54,94,145,1) 100%);
        min-height: 400px;
        margin-top: 10px;
        padding-top: 10px;
    }
</style>
