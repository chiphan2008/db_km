<div class="page-header-full" style="background-image: url('/bookings/assets/img/upload/bg-header-page.png');">
    <div class="container">
        <div class="page-header-content text-center mb-2">
            <h2 class="headr-title text-uppercase m-0">
                Hotel
            </h2>
            <!-- end title -->
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                @if($types)
                @foreach($types as $type)
                <li><a href="/type/{{$type->alias}}">@lang($type->name)</a></li>
                @endforeach
                @endif
              </ol>
            </nav>
            <!-- end breadcrumb   -->
        </div>
        <!-- end  page header content -->
        <div class="search-hotel">
            <form action="" class="form-search-hotel">
                <div class="row align-items-end">
                    <div class="form-group">
                        <label>Nơi muốn đến</label>
                        <select class="custom-select d-block w-100 form-control" required>
                            <option value="all">Khu vực</option>
                            @if($list_city)
                            @foreach($list_city as $city_one)
                            <option value="{{$city_one->alias}}">{{$city_one->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <!-- end form group -->
                    <div class="form-group form-date">
                        <label>Ngày nhân</label>
                        <input id="from" name="from" class="form-control w-100"  type="text" value="23/11/2017">
                    </div>
                    <!-- end form group -->
                    <div class="form-group form-date">
                        <label>Ngày trả</label>
                        <input id="to" name="to" class="form-control w-100"  type="text" value="23/11/2017">
                    </div>
                    <!-- end form group -->
                    <div class="form-group form-number">
                        <label>số người</label>
                        <input type="button" value="-" class="decreaseVal">
                        <!-- <input class=" val" type="number"  disabled> -->
                        <input type="text" min="1" max="22" class="val form-control w-100" placeholder="2 người">
                        <input type="button" value="+" class="increaseVal">
                    </div>
                    <!-- end form group -->
                    <div class="form-group">
                        <label>Giá tiền</label>
                        <select class="custom-select d-block w-100 form-control" required>
                            <option value="">500.00 - 1.000.000</option>
                            <option value="1">1.000.000 - 2.000.000</option>
                            <option value="2">2.000.000 - 3.000.000</option>
                            <option value="3">3.000.000 - 4.000.000</option>
                        </select>
                    </div>
                    <!-- end form group -->
                    <div class="form-group form-submit">
                        <button type="submit" class="btn btn-primary w-100"><i class="icon-search-1"></i> Tìm</button>
                    </div>
                    <!-- end form group -->
                </div>
            </form>
            <!-- end form search hotel -->
        </div>
        <!-- end search hotel -->
    </div>
</div>
