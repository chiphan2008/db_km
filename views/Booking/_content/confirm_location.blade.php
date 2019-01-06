<div class="contact-page">
  <div class="container">
      <div class="row justify-content-center text-center">
          <div class="col-md-8 content-contact p-3 p-md-4 text-left">
              <div class="contact-header mb-3">
                  <div class="row">
                      <div class="col-lg-2 col-md-3">
                          <div class="avata text-center mb-3 mb-md-0">
                              <img class="img-fluid" src="{{$content->avatar}}" alt="{{$content->name}}">
                          </div>
                          <!-- end avata -->
                      </div>
                      <div class="col-lg-10 col-md-9">
                          <h1 class="title">
                              {{$content->name}}
                          </h1>
                          <p class="address">
                              {{$content->address}}, {{$content->_district->name}}, {{$content->_city->name}}, {{$content->_country->name}}
                          </p>
                      </div>
                  </div>
              </div>
              <!-- end  contact header -->
              <div class="contact-about">
                  <p>
                      {{$content->description}}
                  </p>
              </div>
              @if(session('success') || true)
              <h3 class="text-info text-center">
                {{session('success')}}
              </h3>
              @endif
              <!-- end contact about -->
              @if(!session('success'))
              <form class="form-contact" method="post">
              		{{csrf_field()}}
                  <div class="form-group">
                      <input type="text" name="name" class="form-control" placeholder="{{trans('global.name')}}" required>
                  </div>
                  <div class="form-group">
                      <input type="email" name="email" class="form-control" placeholder="{{trans('global.email')}}" required>
                  </div>
                  <div class="form-group">
                      <input type="tel" name="phone" class="form-control" placeholder="{{trans('global.phone')}}" required>
                  </div>
                  <div class="form-group">
                      <textarea class="form-control" name="content" rows="9" placeholder="{{trans('global.content')}}" required></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary px-5 py-3 text-uppercase d-block">{{trans('global.send')}}</button>
              </form>
              @endif
              <!-- end  form contact -->
          </div>
      </div>
  </div>
</div>
<?php
//session()->forget('success');