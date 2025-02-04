<?php
/**
 * @var bool $canUpload
 * @var ChallengeModel|null $challenge
 */

use App\Models\ChallengeModel;
use App\Models\UserModel;
use App\Services\Storage;

/**
 * @var ?ChallengeModel $challenge
 * @var UserModel $user
 */

?>

<?php if ($challenge && !$challenge->isOpen()) {
    ?>
    <div class="card mb-3">
        <div class="card-header text-danger">Challenge has not started yet</div>
        <div class="card-body">
            <p>
                Starts: <strong><?= $challenge->openFrom->format('d.m.Y H:i T'); ?></strong>
            </p>
            <form method="post" action="<?= route('participate'); ?>">
                <?php if ($user->isParticipating) { ?>
                    <p class="text-success">
                        You are participating in this challenge.
                    </p>
                    <button type="submit" class="btn btn-sm btn-danger btn-block">
                        I DON'T want to participate
                    </button>
                <?php } else { ?>
                    <p class="text-danger">
                        You are not participating in this challenge.
                    </p>
                    <button type="submit" class="btn btn-sm btn-success btn-block">
                        I want to participate
                    </button>
                <?php } ?>
            </form>
        </div>
    </div>
    <?php
}

if (!$user->isParticipating) {
    return;
}

?>

<div class="card">
    <div class="card-header">Log an activity</div>
    <div class="card-body">
        <form action="<?= route('upload'); ?>" method="post" enctype="multipart/form-data" id="gpx-form">
            <div class="form-group">
                <label for="customFile">Activity file (GPX):</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="gpx" id="file-gpx">
                    <label class="custom-file-label" id="file-gpx-label" for="file-gpx">
                        Select a file...
                    </label>
                </div>
                <small class="form-text text-muted">
                    How to export from
                    <a href="https://support.strava.com/hc/en-us/articles/216918437-Exporting-your-Data-and-Bulk-Export#GPX"
                       rel="nofollow noopener" target="_blank">Strava</a>
                </small>
            </div>
            <div class="form-group">
                <label for="activityUrl">Activity URL:</label>
                <input type="url" class="form-control" name="activityUrl" id="activityUrl">
                <small class="form-text text-muted">Strava activity page (only if activity is public).</small>
            </div>
            <div class="form-group">
                <label for="comment">Short comment (optional):</label>
                <input type="text" class="form-control" name="comment" id="comment">
                <small class="form-text text-muted">Others will see this, make it motivational!</small>
            </div>
            <div class="form-group">
                <label for="file-photo">
                    Upload a photo
                    (optional, max size <?= Storage::getMaxUploadSize(); ?>M):
                </label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="photo" id="file-photo">
                    <label class="custom-file-label" id="file-photo-label" for="file-photo">
                        Select a photo...
                    </label>
                </div>
            </div>
            <?php if ($canUpload) { ?>
                <div class="text-right">
                    <button id="gpx-upload-button" type="submit" class="btn btn-success">Upload</button>
                </div>
            <?php } else { ?>
                <div class="alert alert-danger p-2 text-center mb-0">
                    <small>Activities cannot be logged at this moment!</small>
                </div>
            <?php } ?>
        </form>
        <script>
            // Disable the upload button during submit to prevent accidental double-submits
            document.getElementById('gpx-form').onsubmit = function () {
                document.getElementById('gpx-upload-button').setAttribute('disabled', 'true');
                document.getElementById('gpx-upload-button').classList.add('disabled');
                document.getElementById('gpx-upload-button').innerHTML = 'Uploading…';
            };

            document.getElementById('file-gpx').onchange = function () {
                document.getElementById('file-gpx-label').textContent = this.files[0].name;
            };

            document.getElementById('file-photo').onchange = function () {
                document.getElementById('file-photo-label').textContent = this.files[0].name;
            };
        </script>
    </div>
</div>