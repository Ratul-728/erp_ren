
<style>
    .inc-pos-row{
        padding: 0 10px;
        margin: 0;
    }
</style>

  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12  rawitem-left">
        <div class="row">
            <input type="hidden"  name="itid" id="itid" value="">
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="code">Item Code *</label>
                    <input type="text" class="form-control" id="code" name="code" value="" required>
                </div>
            </div>

            <div class="col-lg-8 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="nm">Item Name*</label>
                    <input type="text" class="form-control" id="nm" name="nm" value="" required>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="cmbprdtp">Item Type</label>
                    <div class="form-group styled-select">
                        <select name="cmbprdtp" id="cmbprdtp" class="form-control" >
                            <option value="2">Services</option>
                        </select>
                         </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="cmbitmcat">Item Category</label>
                    <div class="form-group styled-select">
                        <select name="cmbitmcat" id="cmbitmcat" class="form-control">
                            <option value="">Name</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="cmbcolor">Business Type</label>
                    <div class="form-group styled-select">
                        <select name="cmbcolor" id="cmbcolor" class="form-control">
                            <option value="">Business</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="size">Company Type</label>
                    <div class="form-group styled-select">
                        <select name="size" id="size" class="form-control">
                            <option value="">Company</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="cmbstyletp">Licence Type</label>
                    <div class="form-group styled-select">
                        <select name="cmbstyletp" id="cmbstyletp" class="form-control">
                            <option value="">License</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="cmbprdtp">Unit</label>
                    <div class="form-group styled-select">
                        <select name="measureUnit" id="measureUnit" class="form-control">
                            <option value="">unit</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="email">Currency</label>
                        <div class="form-group styled-select">
                            <select name="cmbcur" id="cmbcur" class="form-control">
                                <option value="">USD</option>
                            </select>
                        </div>
                </div>
            </div>
             <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                     <label for="cost">Cost</label>
                     <input type="text" class="form-control" id="cost" name="cost" value="" >
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="rate">Rate</label>
                    <input type="text" class="form-control" id="rate" name="rate" value="" >
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="dimension">Dimension</label>
                    <input type="text" class="form-control" id="dimesion" name="dimesion" value="" >
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="weight">Weight</label>
                    <input type="text" class="form-control" id="weight" name="weight" value="" >
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 rawitem-right">
        <div class="form-group">
            <label for="details">Description </label>
            <textarea class="form-control" id="details" name="details" rows="2" ></textarea>
        </div>
        <div class="form-group">
            <strong>Product Image</strong>
            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-primary btn-file btn-file">
                        <i class="fa fa-upload"></i>
                        <input type="file" name="attachment1"  style="display: none;" id="gallery-photo-add" >
                    </span>
                </label>
                <input type="text" class="form-control" readonly>
            </div>
            <span class="help-block form-text text-muted">Try selecting one  files and watch the feedback</span>
        </div>
        <div class="p-1 upload-thumbs ">
            <div class="row prdct-gallery">

            </div>
        </div>
    </div>
