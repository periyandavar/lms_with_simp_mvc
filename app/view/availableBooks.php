<?php
/**
 * Available books
 * php version 7.3.5
 *
 * @category View
 * @package  View
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
defined('VALID_REQ') or exit('Invalid request');
use System\Core\Utility;
if (!isset($books)) {
    return;
}
?>
<article class="main">
    <!-- section 1 -->
    <section>
        <div class="container">
            <div class="row" id='books-list'>
                <?php if (isset($books)):?>
                    <?php foreach ($books as $book): ?>
                        <div class="card cols">
                            <a
                                href="/book/<?php echo $book->id; ?>">

                                <book-element
                                    cover="<?php echo Utility::baseURL()?>/upload/book/<?php echo $book->coverPic;?>"
                                    book="<?php echo $book->name; ?>"
                                    author="<?php echo $book->authors;?>"
                                    id="<?php echo $book->id;?>">
                                </book-element>
                            </a>

                            <div class="card-content">
                                <h3><?php echo $book->name?>
                                </h3>
                                <div class="text-author"><?php echo $book->authors;?>
                                </div>
                                <p><?php echo $book->description;?>
                                </p>
                                <p>Currently 
                                <?php echo ($book->available == 0) ? "no copy"
                                        : ("only " . $book->available
                                        . (($book->available == 1) ? " copy" : " copies"));
                                ?>
                                available</p>
                                <div class="btn-container">
                                    <a class="btn-link"
                                        href="/book/<?php echo $book->id;?>">View
                                        Book</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>
            </div>
            <!-- show more button -->
            <div class="btn-container" id="loadMore">
                <a class="btn-link" onclick="loadMoreBooks(event)">SHOW MORE</a>
            </div>
    </section>
</article>
<script>
    document.getElementById("books").className += " active";
</script>