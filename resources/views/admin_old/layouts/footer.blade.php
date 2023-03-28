<?php use App\Http\Controllers\CommonController; ?>
<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">{{(new CommonController)->GetSetting('copyright')}}</div>
            <div>
                <a href="http://maahirpro.com/privacy_policy.php">Privacy Policy</a>
                &middot;
                <a href="http://maahirpro.com/privacy_policy.php">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
</footer>
