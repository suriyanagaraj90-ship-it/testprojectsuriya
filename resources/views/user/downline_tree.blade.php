@extends('layouts.layout')



@section('title', 'Downline List')



@section('content')

 <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Downline List </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/')}}/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Downline </li>
                </ol>
              </nav>
            </div>
            <div class="row">
              
             
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
               
            
            <div class="row">
                
        <div class="tree">
          <ul style="width:1000px;height:500px;overflow:scroll;">
            <li> <a href="#"><img src="{{url('/')}}/images/1.jpg"></a>
            <ul>
              <li><a href="#"><img src="{{url('/')}}/images/2.jpg"></a>
              <ul>
                <li> <a href="#"><img src="{{url('/')}}/images/3.jpg"></a> </li>
                <li> <a href="#"><img src="{{url('/')}}/images/4.jpg"></a> </li>
                
              </ul>
            </li>
            <li> <a href="#"><img src="images/5.jpg"></a>
            <ul>
              <li> <a href="#"><img src="{{url('/')}}/images/6.jpg"></a> </li>
              <li> <a href="#"><img src="{{url('/')}}/images/7.jpg"></a> </li>
            
            </ul>
          </li>
          <li><a href="#"><img src="{{url('/')}}/images/9.jpg"></a></li>
          <li><a href="#"><img src="{{url('/')}}/images/9.jpg"></a>
          <ul>
              <li> <a href="#"><img src="{{url('/')}}/images/6.jpg"></a> </li>
              <li> <a href="#"><img src="{{url('/')}}/images/7.jpg"></a> </li>
              
            </ul></li>
          <li><a href="#"><img src="{{url('/')}}/images/9.jpg"></a>
          <ul>
              <li> <a href="#"><img src="{{url('/')}}/images/6.jpg"></a> </li>
              <li> <a href="#"><img src="{{url('/')}}/images/7.jpg"></a> </li>
            
            </ul></li>
        </ul>
      </li>
    </ul>
  </div>
</div>


            
                  </div>
                </div>
              </div>
             
             
            </div>
          </div>

<style>
    .tree {
  width: 100%;
  height: auto;
  text-align: center;
}
.tree ul {
  padding-top: 20px;
  position: relative;
  transition: .5s;
}
.tree li {
  display: inline-table;
  text-align: center;
  list-style-type: none;
  position: relative;
  padding: 10px;
  transition: .5s;
}
.tree li::before, .tree li::after {
  content: '';
  position: absolute;
  top: 0;
  right: 50%;
  border-top: 1px solid #ccc;
  width: 51%;
  height: 10px;
}
.tree li::after {
  right: auto;
  left: 50%;
  border-left: 1px solid #ccc;
}
.tree li:only-child::after, .tree li:only-child::before {
  display: none;
}
.tree li:only-child {
  padding-top: 0;
}
.tree li:first-child::before, .tree li:last-child::after {
  border: 0 none;
}
.tree li:last-child::before {
  border-right: 1px solid #ccc;
  border-radius: 0 5px 0 0;
  -webkit-border-radius: 0 5px 0 0;
  -moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after {
  border-radius: 5px 0 0 0;
  -webkit-border-radius: 5px 0 0 0;
  -moz-border-radius: 5px 0 0 0;
}
.tree ul ul::before {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  border-left: 1px solid #ccc;
  width: 0;
  height: 20px;
}
.tree li a {
  border: 1px solid #ccc;
  padding: 10px;
  display: inline-grid;
  border-radius: 5px;
  text-decoration-line: none;
  border-radius: 5px;
  transition: .5s;
}
.tree li a img {
  width: 35px;
  margin-bottom: 10px !important;
  border-radius: 100px;
  margin: auto;
}
.tree li a span {
  border: 1px solid #ccc;
  border-radius: 5px;
  color: #666;
  padding: 8px;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-weight: 500;
}
/*Hover-Section*/
.tree li a:hover, .tree li a:hover i, .tree li a:hover span, .tree li a:hover+ul li a {
  background: #c8e4f8;
  color: #000;
  border: 1px solid #94a0b4;
}
.tree li a:hover+ul li::after, .tree li a:hover+ul li::before, .tree li a:hover+ul::before, .tree li a:hover+ul ul::before {
  border-color: #94a0b4;
}
</style>

@endsection

