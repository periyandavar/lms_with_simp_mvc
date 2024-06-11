<?php
/**
 * Settings
 * php version 7.3.5
 *
 * @category View
 * @package  View
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
defined('VALID_REQ') or exit('Invalid request');
if (!isset($data)) {
    return;
}
?>
<article class="main">
    <section>
        <h1>Settings</h1>
        <hr>
        <form action="/admin/settings" onsubmit="configValidator(event);"
            method="POST">
            <div class="form-input-div">
                <label>Maximum Book Lend <span class="required-star">*</span> </label>
                <input class="form-control" type="number" id="maxbookLend" min="1" name="maxBookLend" maxlength="2"
                    placeholder="Maximum Book Lend" autocomplete="off" required=""
                    value="<?php echo $data->maxBookLend; ?>">
                    <span id="maxbookLend-span" class="span-err-msg"></span>
            </div>
            <div class="form-input-div">
                <label>Maximum Lend Days <span class="required-star">*</span></label>
                <input class="form-control" type="number" id="maxLendDays" min="1" name="maxLendDays" maxlength="2"
                    placeholder="Maximum Lend Days" required=""
                    value="<?php echo $data->maxLendDays; ?>">
                    <span id="maxLendDays-span" class="span-err-msg"></span>
            </div>
            <div class="form-input-div">
                <label>Maximum Book Request  <span class="required-star">*</span></label>
                <input class="form-control" type="number" id="maxBookRequest" min="1" name="maxBookRequest" maxlength="2"
                    placeholder="Maximum Book Request" required=""
                    value="<?php echo $data->maxBookRequest; ?>">
                    <span id="maxBookRequest-span" class="span-err-msg"></span>
            </div>
            <div class="form-input-div">
                <label>Fine Amout per day <span class="required-star">*</span></label>
                <input class="form-control" type="number" id="fineAmtPerDay" min="1" name="fineAmtPerDay"
                    placeholder="Fine Amount per day" $maxlength="2" required=""
                    value="<?php echo $data->fineAmtPerDay; ?>">
                    <span id="fineAmtPerDay-span" class="span-err-msg"></span>
            </div>
            <div class="msg">
                <i>last updation on <?php echo $data->updatedAt ?><i><br>

            </div>
            <div class="form-buttons">
                <button type="submit" class="btn-link">Submit</button>
            </div>
        </form>
    </section>

</article>
<script>
    document.getElementById('settings').className += " active";
</script>