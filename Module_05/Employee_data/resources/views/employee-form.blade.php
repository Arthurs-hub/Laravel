<form name="employee-form" id="employee-form" method="post" action="{{ url('store-form') }}" style="max-width: 600px; margin: 30px auto; background: #2c2f63; padding: 25px; border-radius: 10px; box-shadow: 0 0 15px rgba(75, 0, 130, 0.7); color: #e0e0ff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    @csrf
    <h2 style="text-align: center; margin-bottom: 20px; color: #b39ddb;">Employee Form</h2>
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="name" style="display: block; margin-bottom: 5px; font-weight: 600; color: #d1c4e9;">Name</label>
        <input type="text" id="name" name="name" class="form-control" required="true" style="width: 100%; padding: 8px 12px; border: 1px solid #7e57c2; border-radius: 5px; background: #3f51b5; color: #e8eaf6; font-size: 16px;">
    </div>
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="surname" style="display: block; margin-bottom: 5px; font-weight: 600; color: #d1c4e9;">Surname</label>
        <input type="text" id="surname" name="surname" class="form-control" required="true" style="width: 100%; padding: 8px 12px; border: 1px solid #7e57c2; border-radius: 5px; background: #3f51b5; color: #e8eaf6; font-size: 16px;">
    </div>
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="position" style="display: block; margin-bottom: 5px; font-weight: 600; color: #d1c4e9;">Position</label>
        <input type="text" id="position" name="position" class="form-control" required="true" style="width: 100%; padding: 8px 12px; border: 1px solid #7e57c2; border-radius: 5px; background: #3f51b5; color: #e8eaf6; font-size: 16px;">
    </div>
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="address" style="display: block; margin-bottom: 5px; font-weight: 600; color: #d1c4e9;">Address</label>
        <input type="text" id="address" name="address" class="form-control" required="true" style="width: 100%; padding: 8px 12px; border: 1px solid #7e57c2; border-radius: 5px; background: #3f51b5; color: #e8eaf6; font-size: 16px;">
    </div>
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="email" style="display: block; margin-bottom: 5px; font-weight: 600; color: #d1c4e9;">Email</label>
        <input type="email" id="email" name="email" class="form-control" required="true" style="width: 100%; padding: 8px 12px; border: 1px solid #7e57c2; border-radius: 5px; background: #3f51b5; color: #e8eaf6; font-size: 16px;">
    </div>
    <div class="form-group" style="margin-bottom: 20px;">
        <label for="workData" style="display: block; margin-bottom: 5px; font-weight: 600; color: #d1c4e9;">Work Data</label>
        <textarea id="workData" name="workData" class="form-control" required="true" style="width: 100%; min-height: 80px; padding: 8px 12px; border: 1px solid #7e57c2; border-radius: 5px; background: #3f51b5; color: #e8eaf6; font-size: 16px; font-family: monospace;"></textarea>
    </div>
    <div class="form-group" style="margin-bottom: 20px;">
        <label for="jsonData" style="display: block; margin-bottom: 5px; font-weight: 600; color: #d1c4e9;">JSON Data</label>
        <textarea id="jsonData" name="jsonData" class="form-control" required="true" placeholder='[{"address": {"street": "Kulas Light", "suite": "Apt. 556", "city": "Gwenborough", "zipcode": "92998-3874", "geo": {"lat": "-37.3159", "lng": "81.1496"}}}]' style="width: 100%; min-height: 100px; padding: 8px 12px; border: 1px solid #7e57c2; border-radius: 5px; background: #3f51b5; color: #e8eaf6; font-size: 16px; font-family: monospace;"></textarea>
    </div>
    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; background: #673ab7; border: none; border-radius: 6px; font-size: 18px; font-weight: 700; color: #ede7f6; cursor: pointer; transition: background-color 0.3s ease;">
        Submit
    </button>
</form>

<style>
    body {
        background: linear-gradient(135deg, #3f51b5, #673ab7);
        min-height: 100vh;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    #employee-form input:focus,
    #employee-form textarea:focus {
        outline: none;
        border-color: #9575cd;
        box-shadow: 0 0 8px #9575cd;
        background: #5c6bc0;
        color: #fff;
    }

    #employee-form button:hover {
        background-color: #512da8;
    }
</style>