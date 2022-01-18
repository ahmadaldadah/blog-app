
<div class="container">
    <div class="row mt-4">
        @card(['title'=>'Most Commented'])
        @slot('subtitle')
            What people are currently most active at
        @endslot
        @slot('items')
            @foreach($mostCommented as $post)
                <li class="list-group-item">
                    <a href="{{ route('posts.show',['post'=>$post->id]) }}">
                        {{$post->title}}
                    </a>

                </li>
            @endforeach
        @endslot
        @endcard
    </div>
    <div class="row mt-4">

        @card(['title'=>'Most Active'])
        @slot('subtitle')
            Writers with most post written
        @endslot
        @slot('items',collect($mostActive)->pluck('name'))
        @endcard
    </div>

    <div class="row mt-4">

        @card(['title'=>'Most Active Last Month'])
        @slot('subtitle')
            Writers with most post written in the last month
        @endslot
        @slot('items',collect($mostActiveLastMonth)->pluck('name'))
        @endcard
    </div>

</div>
