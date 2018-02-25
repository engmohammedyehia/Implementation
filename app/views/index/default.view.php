<div class="wrapper">
    <?php $messages = $this->messenger->getMessages(); if(!empty($messages)): foreach ($messages as $message): ?>
        <p class="message t<?= $message[1] ?>"><?= $message[0] ?><a href="" class="closeBtn"><i class="fa fa-times"></i></a></p>
    <?php endforeach;endif; ?>
    <form method="post" enctype="multipart/form-data" class="appForm">
        <fieldset>
            <legend>HoETL Tool</legend>
            <div class="input_wrapper_other n100">
                <label for="floated">Choose a file from you machine (.csv)</label>
                <input required type="file" name="data" id="data" accept=".csv">
            </div>
            <div class="input_wrapper_other n100">
                <label class="floated">Choose the output format <span class="required">*</span></label>
                <label class="radio">
                    <input required type="radio" name="output_type" id="output_type" value="JSONParser">
                    <div class="radio_button"></div>
                    <span>JSON Format</span>
                </label>
                <label class="radio">
                    <input required type="radio" name="output_type" id="output_type" value="XMLParser">
                    <div class="radio_button"></div>
                    <span>XML Format</span>
                </label>
            </div>
            <div class="input_wrapper_other n100">
                <label class="floated">Filter output (Un-check the unwanted column)</label>
                <label class="checkbox block left">
                    <input data-switch="name" type="checkbox" name="filter[]" checked value="0">
                    <div class="checkbox_button"></div>
                    <span>Name</span>
                </label>
                <label class="checkbox block left">
                    <input data-switch="address" type="checkbox" name="filter[]" checked value="1">
                    <div class="checkbox_button"></div>
                    <span>Address</span>
                </label>
                <label class="checkbox block left">
                    <input data-switch="stars" type="checkbox" name="filter[]" checked value="2">
                    <div class="checkbox_button"></div>
                    <span>Stars</span>
                </label>
                <label class="checkbox block left">
                    <input data-switch="contact" type="checkbox" name="filter[]" checked value="3">
                    <div class="checkbox_button"></div>
                    <span>Contact</span>
                </label>
                <label class="checkbox block left">
                    <input type="checkbox" name="filter[]" checked value="4">
                    <div class="checkbox_button"></div>
                    <span>Phone</span>
                </label>
                <label class="checkbox block left">
                    <input type="checkbox" name="filter[]" checked value="5">
                    <div class="checkbox_button"></div>
                    <span>URL</span>
                </label>
            </div>
            <div data-filter-name="name" class="input_wrapper_other n25 border">
                <label class="floated">Sort by name</label>
                <label class="radio">
                    <input type="radio" name="sort[0]" value="1">
                    <div class="radio_button"></div>
                    <span>ASC</span>
                </label>
                <label class="radio">
                    <input type="radio" name="sort[0]" value="2">
                    <div class="radio_button"></div>
                    <span>DESC</span>
                </label>
            </div>
            <div data-filter-name="address" class="input_wrapper_other n25 padding border">
                <label class="floated">Sort by address</label>
                <label class="radio">
                    <input type="radio" name="sort[1]" value="1">
                    <div class="radio_button"></div>
                    <span>ASC</span>
                </label>
                <label class="radio">
                    <input type="radio" name="sort[1]" value="2">
                    <div class="radio_button"></div>
                    <span>DESC</span>
                </label>
            </div>
            <div data-filter-name="stars" class="input_wrapper_other n25 padding border">
                <label class="floated">Sort by stars</label>
                <label class="radio">
                    <input type="radio" name="sort[2]" value="1">
                    <div class="radio_button"></div>
                    <span>ASC</span>
                </label>
                <label class="radio">
                    <input type="radio" name="sort[2]" value="2">
                    <div class="radio_button"></div>
                    <span>DESC</span>
                </label>
            </div>
            <div data-filter-name="contact" class="input_wrapper_other n25 padding">
                <label class="floated">Sort by contact</label>
                <label class="radio">
                    <input type="radio" name="sort[3]" value="1">
                    <div class="radio_button"></div>
                    <span>ASC</span>
                </label>
                <label class="radio">
                    <input type="radio" name="sort[3]" value="2">
                    <div class="radio_button"></div>
                    <span>DESC</span>
                </label>
            </div>
            <input type="submit" name="submit" value="Submit">
        </fieldset>
    </form>
</div>