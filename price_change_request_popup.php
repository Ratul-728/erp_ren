
    <h4>Submit for Price Approval</h4>
    <form action="your-server-endpoint" method="POST">
         <div class="form-group">
            <label for="newPrice">Current Price:</label>
            <input type="hidden"  name="prodid" id="prodid" value="<?=$_REQUEST['product']?>">
            <input type="text" class="form-control" id="currentRate" readonly name="currentRate" value="<?=$_REQUEST['current_rate']?>" placeholder="Current price">
        </div>
        <div class="form-group">
            <label for="newPrice">New Price:</label>
            <input type="text" class="form-control" id="newRate" required name="newRate" placeholder="Enter new price">
        </div>

        <div class="form-group">
            <label for="reason">Reason:</label>
            <textarea class="form-control" id="reason" name="reason" rows="4" placeholder="Enter reason for price change"></textarea>
        </div>
        <input type="hidden" name="userid" id="userid" value="<?=$_REQUEST['userid']?>">
        
        
    </form>
