<?php
/**
 * View Book
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
<div class="row-container">
    <div class="row">
        <div class="cols">
            <div class="text-container">
                <div class='msg-card'>
                    <h1><?php echo $book->name?>
                    </h1><br>
                    <div class="row">
                        <div class="cols col-3 img-cover">
                            <img
                                src="/upload/book/<?php echo $book->coverpic; ?>">
                            <h3>Location</h3>
                            <p> <?php echo $book->location;?>
                            </p>
                        </div>
                        <div class="cols col-4">
                            <div class="img-details">
                                <h3> Authors </h3>
                                <div class='text-author'>
                                    <ul class="styled-list">
                                        <?php
                                        foreach (explode(",", $book->authors) as $author) {
                                            echo "<li>  $author </li>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <h3> About the Book </h3>
                                <p class="text-para"><?php echo $book->description; ?>
                                </p>
                                <h3> Categories </h3>
                                <ul class="styled-list">
                                    <?php
                                    foreach (explode(",", $book->categories) as $category) {
                                        echo "<li>  $category </li>";
                                    }
                                    ?>
                                </ul>
                                <h3>ISBN Number</h3>
                                <p class="text-para"> <?php echo $book->isbn; ?>
                                </p>
                                <p class="stack-msg">* Currently <i><?php echo ($book->available == 0) ? "no" : $book->available; ?>
                                    </i> <?php echo ($book->available == 1) ? "copy" : "copies"; ?>
                                    available</p>
                                <div class="form-buttons">
                                    <a class="btn-link" href="/login">Request to Lend</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>