<form action="{{ route('video.store') }}" method="POST" enctype="multipart/form-data">
    @method('post')
    @csrf
    <input type="file" name="video">
    <input type="submit" value="Ok">
</form>
