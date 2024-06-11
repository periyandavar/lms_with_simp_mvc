<?php
/**
 * Add book
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
                    <h1>Add New Book &nbsp;<a class="btn-link" href="/book-management">View Available Books</a></h1>
                    <hr>
                </div>
            </div>

            <form action="" enctype="multipart/form-data" 
                onsubmit="<?php
                        (isset($book) && isset($book->id))
                            ? print("editBookFormValidator(event)") 
                            : print("bookFormValidator(event)"); ?>;" 
                            method="post">
                <div class="form-input-div">
                    <label>Name <span class="required-star">*</span></label>
                    <input class="form-control" type="text" 
                    <?php if (isset($book->name)) {
                        echo "value='".$book->name."'";
                    }?>
                    id="bookname" name="name" autocomplete="off" placeholder="Book Name" required="">
                    <span id="bookname-span" class="span-err-msg"></span>
                </div>
                <div class="form-input-div">
                    <label>Location <span class="required-star">*</span></label>
                    <input class="form-control" type="text" 
                    <?php if (isset($book->location)) {
                        echo "value='".$book->location."'";
                    }?>
                    id="location" name="location" autocomplete="off" placeholder="Book Location" required="">
                    <span id="location-span" class="span-err-msg"></span>

                </div>

                <div class="form-input-div">
                    <label>Authors <span class="required-star">*</span></label>
                    <div class="form-control list-group" contenteditable="true" id="authorslist">
                        <?php
                        if (isset($book)) {
                            $dataCodes = explode(",", $book->authorCodes);
                            $dataValues = explode(",", $book->authors);
                            for ($i=0; $i < count($dataCodes); $i++) {
                                if ($dataValues[$i] != '') {
                                    echo '<span contenteditable="false" class="list-group-item" id="list-group-item-' . $dataCodes[$i] . '" data-value="' . $dataCodes[$i] . '">' . $dataValues[$i] . ' <span class="badge removeItems" id="removeItem-'.$dataCodes[$i].'" data-id="' . $dataCodes[$i] . '">X</span></span>&nbsp;';
                                }
                            }
                        }
                        ?>
                    </div>
                    <span id="selected-author-span" class="span-err-msg"></span>
                    <input type="hidden" 
                    <?php if (isset($book->authorCodes)) {
                                echo "value='".$book->authorCodes.",'";
                    }?>
                        name="author" required id="selected-author">
                </div>
                
                <!-- <div class="form-input-div">
                    <label>Category</label>
                    <input class="form-control autocomplete" type="text" id="search-category" autocomplete="off"
                        placeholder="Search Category">
                </div> -->
                <div class="form-input-div">
                    <label>Categories <span class="required-star">*</span></label>
                    <div contenteditable="true" class="form-control list-group " id="catlist">
                        <?php
                        if (isset($book)) {
                            $dataCodes = explode(",", $book->categoryCodes);
                            $dataValues = explode(",", $book->categories);
                            for ($i=0; $i < count($dataCodes); $i++) {
                                if ($dataValues[$i] != '') {
                                    echo '<span contenteditable="false" class="list-group-item" id="list-group-item-' . $dataCodes[$i] . '" data-value="' . $dataCodes[$i] . '">' . $dataValues[$i] . ' <span class="badge removeItems" id="removeItem-'.$dataCodes[$i].'" data-id="' . $dataCodes[$i] . '">X</span></span>&nbsp;';
                                }
                            }
                        }
                        ?>
                    </div>
                    <span id="selected-category-span" class="span-err-msg"></span>
                    <input type="hidden" 
                        <?php if (isset($book->categoryCodes)) {
                            echo "value='".$book->categoryCodes.",'";
                        }?>
                        name="category" required id="selected-category">
                </div>

                <div class="form-input-div">
                    <label>Publication <span class="required-star">*</span></label>
                    <input class="form-control" type="text" 
                        <?php if (isset($book->publication)) {
                            echo "value='".$book->publication."'";
                        }?>
                    id="publication" name="publication" autocomplete="off" placeholder="Book Publication" required="">
                    <span id="publication-span" class="span-err-msg"></span>
                </div>
                <div class="form-input-div">
                    <label>ISBN <span class="required-star">*</span></label>
                    <input onblur="isbnAvailability(event.target.value, 'isbn-span', '<?php echo (isset($book) && isset($book->id)) ? $book->id : 0;?>')" class="form-control" type="text" 
                        <?php if (isset($book->isbn)) {
                            echo "value='".$book->isbn."'";
                        }?>
                    id="isbn" name="isbn" autocomplete="off" placeholder="ISBN Number" required="">
                    <span id="isbn-span" class="span-err-msg"></span>
                </div>
                <div class="form-input-div">
                    <label>Price <span class="required-star">*</span></label>
                    <input class="form-control" type="number" min="0" id="price" name="price" 
                        <?php if (isset($book->price)) {
                            echo "value='".$book->price."'";
                        }?>
                    autocomplete="off" placeholder="Price" required="">
                    <span id="price-span" class="span-err-msg"></span>
                </div>
                <div class="form-input-div">
                    <label>Stack <span class="required-star">*</span></label>
                    <input class="form-control" type="number" min="0" id="stack" name="stack" 
                        <?php if (isset($book->stack)) {
                            echo "value='".$book->stack."'";
                        }?>
                    autocomplete="off" placeholder="Stack" required="">
                    <span id="stack-span" class="span-err-msg"></span>
                </div>
                <div class="form-input-div">
                    <label>Description <span class="required-star">*</span></label>
                    <textarea class="form-control" id="description" name="description" placeholder="Description"
                        required=""><?php if (isset($book->description)) {
                                        echo $book->description;
                                    }?></textarea><span id="description-span" class="span-err-msg"></span>
                </div>

                <div class="form-input-div">
                    <label>Cover Picture <span class="required-star">*</span></label>
                        <?php if ((isset($book) && isset($book->id))) {
                            echo '<input class="form-control" type="file" id="coverPic" name="coverPic" accept=".jpg, .png" onchange="changePreview(event);">';
                            echo '<img class="file-preview" id="file-preview" src="/upload/book/' . $book->coverPic .'">';
                        } else {
                            echo '<input class="form-control" type="file" id="coverPic" name="coverPic" accept=".jpg, .png" onchange="changePreview(event);" required="">';
                        } ?>
                        <span id="coverPic-span" class="span-err-msg"></span>
                </div>
                <div class="form-buttons">
                    <button type="submit" id="submit-btn" value='1' class="btn-link">
                        <?php (isset($book) && isset($book->id)) ? print("Update") : print("Add"); ?>
                    </button>
                </div>
            </form>
        </div>
    </section>
</article>
<script>
    document.getElementById('books').className += " active";
    autocomplete(document.getElementById("authorslist"), document.getElementById("authorslist"), "/authors/", null, "selected-author",true );
    autocomplete(document.getElementById("catlist"), document.getElementById("catlist"), "/categories/", null, "selected-category", true);
</script>