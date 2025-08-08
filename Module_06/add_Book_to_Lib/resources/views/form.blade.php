<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Book to Library</title>
</head>

<body style="background: linear-gradient(135deg, #FFE5B4 0%, #FFB347 100%); min-height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <div class="add-books__form-wrapper" style="background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%); padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 8px 20px rgba(255, 140, 0, 0.4);">
        <h2 style="color: #fff1c9ff; text-align: center; margin-bottom: 25px; text-shadow: 1px 1px 3px rgba(241, 228, 34, 0.94); font-weight: 700;">Add book to library</h2>
        <form name="add-new-book" id="add-new-book" method="post" action="{{ url('store') }}" style="color: #ffffffff;">
            @csrf
            @if ($errors->any())
            <div class="alert alert-danger" style="background-color: rgba(255, 69, 0, 0.8); border-radius: 8px; padding: 15px; color: #fff;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="form-section" style="margin-bottom: 20px;">
                <label for="title" style="display: block; font-weight: 600; margin-bottom: 6px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Title</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required
                    style="width: 94%; padding: 10px 14px; border-radius: 8px; border: none; font-size: 16px; box-shadow: inset 0 0 6px rgba(255, 140, 0, 0.6); outline: none;"
                    onfocus="this.style.boxShadow='0 0 8px 2px #FFD580';" onblur="this.style.boxShadow='inset 0 0 6px rgba(255, 140, 0, 0.6)';">
            </div>
            <div class="form-section" style="margin-bottom: 20px;">
                <label for="author" style="display: block; font-weight: 600; margin-bottom: 6px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Author</label>
                <input type="text" id="author" name="author" class="form-control" value="{{ old('author') }}" required
                    style="width: 94%; padding: 10px 14px; border-radius: 8px; border: none; font-size: 16px; box-shadow: inset 0 0 6px rgba(255, 140, 0, 0.6); outline: none;"
                    onfocus="this.style.boxShadow='0 0 8px 2px #FFD580';" onblur="this.style.boxShadow='inset 0 0 6px rgba(255, 140, 0, 0.6)';">
            </div>
            <div class="form-section" style="margin-bottom: 25px;">
                <label for="genre" style="display: block; font-weight: 600; margin-bottom: 6px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">Choose Genre:</label>
                <select name="genre" id="genre" required
                    style="width: 100%; padding: 10px 14px; border-radius: 8px; border: none; font-size: 16px; box-shadow: inset 0 0 6px rgba(255, 140, 0, 0.6); background-color: #fff; color: #333; box-sizing: border-box; appearance: none; -webkit-appearance: none; -moz-appearance: none; outline: none;"
                    onfocus="this.style.boxShadow='0 0 8px 2px #FFD580';" onblur="this.style.boxShadow='inset 0 0 6px rgba(255, 140, 0, 0.6)';">
                    <option value="">Select genre</option>
                    <option value="fantasy" {{ old('genre') == 'fantasy' ? 'selected' : '' }}>Fantasy</option>
                    <option value="sci-fi" {{ old('genre') == 'sci-fi' ? 'selected' : '' }}>Sci-Fi</option>
                    <option value="mystery" {{ old('genre') == 'mystery' ? 'selected' : '' }}>Mystery</option>
                    <option value="drama" {{ old('genre') == 'drama' ? 'selected' : '' }}>Drama</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"
                style="background-color: #ff7f00; border: none; padding: 12px 25px; border-radius: 8px; font-size: 18px; font-weight: 700; color: #ffffffff; cursor: pointer; box-shadow: 0 4px 12px rgba(255, 127, 0, 0.6); transition: background-color 0.3s ease;">
                Submit
            </button>
        </form>
    </div>
</body>

</html>