@props(['imageName', 'imageHeight'])

<img class="object-cover w-full rounded-t-lg h-64 md:h-auto md:w-64 md:rounded-none md:rounded-s-lg" style="height:{{ $imageHeight ?? '' }}px;" src="{{$imageName}}" alt="">
