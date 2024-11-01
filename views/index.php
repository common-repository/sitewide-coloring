<?php
$boxes = json_decode($options['sitewide_box']);
?>
<div class="wrap">
    <h2>SITEWIDE BANNERS</h2>
    <form method="POST">
        <?php if (isset($options['save']) && $options['save'] && empty($errors)) { ?>
            <div id="setting-error-settings_updated" class="updated settings-error"> 
                <p><strong>Settings saved.</strong></p>
            </div>
        <?php } ?>
        <div style="display: none;">
            <table width="1168">
                <tbody id="hidden_box">
                    <tr>
                        <td class="block_wrap">
                            Block #<span class="block_number"></span>
                        </td>
                        <td colspan="2" class="desktop_content_block"><textarea name="sitewide[desktop_content][]" placeholder="Enter html or text" style="resize:vertical;"></textarea></td>
                        <td colspan="2" class="mobile_content_block"><textarea name="sitewide[mobile_content][]" placeholder="Enter html or text" style="resize:vertical;"></textarea></td>
                        <td>
                            <select class="position dropdown" name="sitewide[position][]">
                                <?php
                                foreach ($this->positions as $position) {
                                    echo '<option>' . $position . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select class="paragraph dropdown" name="sitewide[paragraph][]">
                                <?php
                                foreach ($this->paragraphs as $paragraph) {
                                    echo '<option>' . $paragraph . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <a class="remove_box" href="#" >Remove</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <table width="1168" class="wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-title" style="">
                        Title
                    </th>
                    <th colspan="2" scope="col" class="manage-column column-title" style="">
                        <label>
                            <input type="radio" name="banner_device_toggle" value="0" checked id="desktop_content_btn" />
                            Desktop content
                        </label>
                        <label>
                            <input type="radio" name="banner_device_toggle" value="1" id="mobile_content_btn" />
                            Mobile content (<=600px)
                        </label>
                    </th>
                    <th scope="col" class="manage-column column-title" style="">
                        Position
                    </th>
                    <th scope="col" class="manage-column column-title" style="">
                        Paragraph
                    </th>
                    <th scope="col" class="manage-column column-title" style="">
                        Remove
                    </th>
                </tr>
            </thead>

            <tbody id="box_list">
                <?php
                if ($boxes) {
                    foreach ($boxes as $box) {
                        ?>
                        <tr>
                            <td class="block_wrap">
                                Block #<span class="block_number"></span>
                            </td>
                            <td colspan="2" class="desktop_content_block"><textarea name="sitewide[desktop_content][]" placeholder="Enter html or text" style="resize:vertical;"><?php echo $box->desktop_content; ?></textarea></td>
                            <td colspan="2" class="mobile_content_block"><textarea name="sitewide[mobile_content][]" placeholder="Enter html or text" style="resize:vertical;"><?php echo $box->mobile_content; ?></textarea></td>
                            <td><select class="position dropdown" name="sitewide[position][]">
                                    <?php
                                    foreach ($this->positions as $position) {
                                        $selected = ($box->position == $position) ? 'selected="selected"' : '';
                                        echo '<option ' . $selected . '>' . $position . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><select class="paragraph dropdown" name="sitewide[paragraph][]">
                                    <?php
                                    foreach ($this->paragraphs as $paragraph) {
                                        $selected = ($box->paragraph == $paragraph) ? 'selected="selected"' : '';
                                        echo '<option ' . $selected . '>' . $paragraph . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <a class="remove_box" href="#">Remove</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <p class="submit banners_list_submit    ">
            <button class="button button-primary" id="add_block">Add block</button>
            <input type="submit" class="button button-primary save_changes" value="Save Changes">
        </p>

        <div id="display_poststuff" class="display_sttings">
            <div id="postimagediv" class="postbox" style="display: block;">
                <h3 class="sitewide_block_title">DISPLAY SETTINGS</h3>
                <div class="inside">
                    <div class="sitewide_display_block sitewide_display_block_blocks">

                    </div>
                    <div class="sitewide_display_block sitewide_display_block_slugs">
                        <label for="sitewide[slugs]">Do not display blocks on pages with the following slugs:</label>
                        <textarea name="sitewide[slugs]" id="sitewide_slugs" placeholder="Enter slug"><?php echo $options['sitewide_slugs']; ?></textarea>
                    </div>
                    <div class="sitewide_display_block sitewide_display_block_ids">
                        <select id="display_rules" class="dropdown" name="sitewide[display_rules]">
                            <?php
                            foreach ($this->display as $key => $value) {
                                $selected = ($options['sitewide_display_rules'] == $key) ? 'selected="selected"' : '';
                                echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                            }
                            ?>
                        </select>
                        <textarea name="sitewide[page_ids]" placeholder = "Field is empty" id="sitewide_page_ids"><?php echo $options['sitewide_page_ids']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <p class="submit banners_list_submit">
            <input type="submit" name="submit" id="submit" class="button button-primary save_changes" value="Save Changes">
        </p>

        <div id="poststuff" class="sitewide_example">
            <div id="postimagediv" class="postbox" style="display: block;">
                <h3 class="sitewide_block_title">Example</h3>
                <div class="inside">
                    <p id="paragraph_1">
                        Lorem ipsum dolor sit amet, vero movet delenit cum id, mei graeci principes at, mea clita epicuri ea. Eu habeo tempor temporibus sit, nec ut ancillae voluptatibus, tamquam dignissim eos cu. Sed idque lorem cu. Virtute minimum vis ei, ut bonorum detracto iracundia eam, vim in iudico soluta deserunt.
                        Lorem ipsum dolor sit amet, vero movet delenit cum id, mei graeci principes at, mea clita epicuri ea. Eu habeo tempor temporibus sit, nec ut ancillae voluptatibus, tamquam dignissim eos cu. Sed idque lorem cu. Virtute minimum vis ei, ut bonorum detracto iracundia eam, vim in iudico soluta deserunt.
                    </p>
                    <p id="paragraph_2">
                        Probo reformidans eu sea. Cu delenit vulputate eos. Illud malorum dignissim no has, oblique vivendum mel et. Dicta nominavi usu no, mei te errem noluisse.Probo reformidans eu sea. Cu delenit vulputate eos. Illud malorum dignissim no has, oblique vivendum mel et. Dicta nominavi usu no, mei te errem noluisse.Probo reformidans eu sea. Cu delenit vulputate eos. Illud malorum dignissim no has, oblique vivendum mel et. Dicta nominavi usu no, mei te errem noluisse.Probo reformidans eu sea. Cu delenit vulputate eos. Illud malorum dignissim no has, oblique vivendum mel et. Dicta nominavi usu no, mei te errem noluisse.
                    </p>
                    <p id="paragraph_3">
                        No mea doctus incorrupte, ea enim sint accommodare usu. Ei etiam cetero voluptatibus mei, admodum expetenda assentior ea eam, latine appetere intellegebat no pro. Quo ad dicant everti commune. Est dicit semper honestatis in. Omnes alterum mei ei, sale quas verear sed ei.No mea doctus incorrupte, ea enim sint accommodare usu. Ei etiam cetero voluptatibus mei, admodum expetenda assentior ea eam, latine appetere intellegebat no pro. Quo ad dicant everti commune. Est dicit semper honestatis in. Omnes alterum mei ei, sale quas verear sed ei.
                    </p>
                    <p id="paragraph_4">
                        Ea vidisse volutpat sea, prima efficiantur sed cu. Id stet graeci accusamus sit. Has in sapientem adipiscing comprehensam, petentium signiferumque at pro. Ea has assum gloriatur expetendis, duo at dicit solet. Eam mundi nemore no.Ea vidisse volutpat sea, prima efficiantur sed cu. Id stet graeci accusamus sit. Has in sapientem adipiscing comprehensam, petentium signiferumque at pro. Ea has assum gloriatur expetendis, duo at dicit solet. Eam mundi nemore no.
                    </p>
                    <p id="paragraph_5">
                        Pro persius senserit concludaturque cu, at feugiat ceteros eam. Ei stet prodesset duo, per ex vidit delectus convenire. Eam autem sanctus an, ne nec iudico tibique. Mei te inani fabulas, inciderint theophrastus ne per. Cu rebum putant vim, no regione platonem sea.Pro persius senserit concludaturque cu, at feugiat ceteros eam. Ei stet prodesset duo, per ex vidit delectus convenire. Eam autem sanctus an, ne nec iudico tibique. Mei te inani fabulas, inciderint theophrastus ne per. Cu rebum putant vim, no regione platonem sea.
                    </p>
                    <p id="paragraph_last">
                        Probo reformidans eu sea. Cu delenit vulputate eos. Illud malorum dignissim no has, oblique vivendum mel et. Dicta nominavi usu no, mei te errem noluisse.Probo reformidans eu sea. Cu delenit vulputate eos. Illud malorum dignissim no has, oblique vivendum mel et. Dicta nominavi usu no, mei te errem noluisse.Probo reformidans eu sea. Cu delenit vulputate eos. Illud malorum dignissim no has, oblique vivendum mel et. Dicta nominavi usu no, mei te errem noluisse.
                    </p>
                    <div style="clear: both"></div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .banner_lists{
        overflow: hidden;
    }
    .banner_lists li{
        width:33%;
        float:left;

    }
    .block_example {
        border: 1px solid black; 
        padding: 15px; 
        width: 90px;
        text-align: center;
        margin: 5px auto;
        background-color: rgba(194, 222, 255, 0.89);
    }
    .block_example.left {
        float: left;
        margin: 5px;
    }
    .block_example.right {
        float: right;
        margin: 5px;
    }

    .faq_banners{
        width: 100%;
    }

    .faq_banners .col-sm-6{
        width: 44%; 
    }

    ul.available_steps_styles{
        float: left;
    }

    ul.available_steps_styles li{
        width: 33%;
        float:left;
        font-weight: bold;
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        function addBox() {
            var line = $("#hidden_box").html();
            $("#box_list").append(line);
            updateBoxes();
        }

        function updateBoxes() {
            $(".block_example").remove();
            $("#box_list .block_number").each(function (index) {
                $(this).attr("data-id", index + 1);
                $(this).text(index + 1);
                var position = $(this).parent().parent().find('select.position').val();
                var paragraph_value = parseInt($(this).parent().parent().find('select.paragraph').val());
                var paragraph = (paragraph_value) ? paragraph_value : "last";
                if (position == 'Below') {
                    $("#paragraph_" + paragraph).append('<div class="block_example">Block #' + (index + 1) + '</div>');
                } else if (position == 'Left') {
                    $("#paragraph_" + paragraph).prepend('<div class="block_example left">Block #' + (index + 1) + '</div>');
                } else if (position == 'Right') {
                    $("#paragraph_" + paragraph).prepend('<div class="block_example right">Block #' + (index + 1) + '</div>');
                } else {
                    $("#paragraph_" + paragraph).prepend('<div class="block_example">Block #' + (index + 1) + '</div>');
                }
            });
        }
        if (!$("#box_list .block_number").length) {
            addBox();
            addBox();
            addBox();
        } else {
            addBox();
        }

        $("body").on("change", ".position, .paragraph", function () {
            updateBoxes();
        });

        $("body").on("click", "#add_block", function (e) {
            addBox();
            e.preventDefault();
        });
        $("body").on("click", ".remove_box", function (e) {
            $(this).parent().parent().remove();
            e.preventDefault();
        });
    });
</script>
