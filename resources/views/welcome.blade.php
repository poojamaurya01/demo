@extends('layouts')
@section('content')
    <div class="conatiner p-4">
        <div class="row p-4">
            
            <div class="col-4">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <form id="filter_apply">
                        <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Topic
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                @foreach ($topics as $topic)
                                    <div>
                                        <label for="{{$topic->name}}"><input type="checkbox" class="topic-checkbox" id="{{$topic->name}}" name="{{$topic->name}}" value="{{ $topic->name }}"> {{ucfirst($topic->name)}}</label>
                                    </div>   
                                @endforeach
                            </div>
                        </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                    Price
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    @php
                                        $prices = [50, 100, 150, 200, 250, 300, 210, 240];
                                    @endphp

                                    <div class="form-group">
                                        <label for="minPrice">Min Price:</label>
                                        <input class="form-control" type="number" name="min_price" id="minPrice" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="maxPrice">Max Price:</label>
                                        <input class="form-control" type="number" name="max_price" id="maxPrice" value="">
                                    </div>

                                    @foreach ($prices as $price)
                                        <div>
                                            <label for="price_{{ $price }}">
                                                <input type="checkbox" name="price_range[]" value="{{ $price }}" id="price_{{ $price }}">
                                                {{ $price }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <button type="button" id="apply_filter_btn" class="btn btn-primary mt-4" >Apply Filter</button>
                    </form>
                </div>
            </div>

            <div class="col-8">
                <div class="float-end w-50 d-flex">
                    <label class="w-100" for="orderBy">Order By:</label>
                    <select class="form-select" name="orderBy" id="orderBy">
                        <option value="" selected disabled>Select</option>
                        <option value="desc">Newest</option>
                        <option value="asc">Oldest</option>
                    </select>
                </div>

                <table id="courses-table" class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Topic</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        $(document).ready(function () {
            var selectedPrices ;
            var selectedTopics ;
            var minPrice;
            var maxPrice;
            var orderBy;

            var course_data = $('#courses-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    type: 'GET',
                    url: '{!! route('courses.datatable') !!}',
                    data (d) {
                        d.price_range = selectedPrices;
                        d.selected_topics = selectedTopics;
                        d.min_price = minPrice;
                        d.max_price = maxPrice;
                        d.order_by = orderBy;
                    }
                },
                columns: [
                    { data: 'name', name: 'name' , orderable: false },
                    { data: 'topic_name', name: 'topic_name' , orderable: false },
                    { data: 'price_range', name: 'price_range' , orderable: false}
                ]
            });
            
            $('#orderBy').on('change', function()
            {
                orderBy= $(this).val();
                course_data.ajax.reload();
            });
       
            $('#apply_filter_btn').on('click', function (e) {
                e.preventDefault();
                selectedPrices = $('input[name="price_range[]"]:checked').map(function(){
                    return this.value;
                }).get();

                selectedTopics = $('.topic-checkbox:checked').map(function(){
                    return this.name;
                }).get();

                minPrice = $('#minPrice').val();
                maxPrice = $('#maxPrice').val();

                course_data.ajax.reload();
            });
        });
    </script>
 
@endsection
