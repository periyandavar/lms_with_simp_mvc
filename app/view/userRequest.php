<?php
/**
 * User Request
 * php version 7.3.5
 *
 * @category View
 * @package  View
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
defined('VALID_REQ') or exit('Invalid request');
?>
<article class="main">
    <section>
        <div class="container div-card">
            <div class="row">
                <div class="cols col-9">
                    <h1>User Request</h1>
                    <hr>
                </div>
            </div>

            <form action="" enctype="multipart/form-data" method="post">
                <div class="row">
                    <div class="cols col-4">
                        <div class="form-input-div">
                            <label>User name</label>
                            <input disabled class="form-control disabled" type="text" id="username" name="username"
                                value="<?php echo $data->userName; ?>"
                                placeholder="User Name" required="">
                        </div>
                    </div>

                    <div class="cols col-4">
                        <div class="form-input-div">
                            <label>User Details</label>
                            <div class="form-control div-like-textarea disabled" id="userdetails">
                                <?php
                                echo $data->fullName . "<br>";
                                echo $data->mobile . "<br>";
                                echo $data->email . "<br>";
                                echo 'lent books '.$data->lent ;
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="cols col-4">
                        <div class="form-input-div">
                            <label>ISBN</label>
                            <input disabled class="form-control disabled" type="text" id="isbn"
                                value="<?php echo $data->isbn; ?>"
                                name="isbn" placeholder="Book Name" required="">
                        </div>
                    </div>

                    <div class="cols col-4">
                        <div class="form-input-div">
                            <label>Book Details</label>
                            <div class="form-control div-like-textarea disabled" id="bookdetails">
                                <div class="img-wrapper">
                                    <?php
                                        echo "<img src='/upload/book/$data->coverPic'>";
                                    ?>
                                </div>
                                <div class="text-wrapper">
                                    <?php
                                        echo $data->name . '<br>';
                                        echo "location: $data->location <br>";
                                        echo "$data->publication<br>";
                                        $available = $data->available == 0 ? 'no' : $data->available;
                                        $available .= $data->available == 1 ? ' copy' : ' copies';
                                        echo "$available available";
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cols col-9">
                        <div class="form-input-div">
                            <label>Comments (if any)</label>
                            <textarea class="form-control" id="comments" name="comments"
                                placeholder="comments"><?php echo $data->comments; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-buttons">
                    <?php if (isset($data->msg)): ?>
                    <div class='msg'>
                        <?php echo $data->msg; ?><br><br>
                    </div>
                    <?php else:?>
                    <button type="submit" name='status' value='2' class="btn-link positive">Issue</button>
                    <?php endif; ?>
                    <button type="submit" name='status' value='1' class="btn-link">Approve</button>
 
                    <button type="submit" name='status' value='0' class="btn-link negative">Reject</button>
                </div>
        </div>
        </form>
        </div>
    </section>
</article>
<script>
    document.getElementById('request').className += " active";
</script>