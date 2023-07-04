@extends('layout.app')
@section('content')
    <div class="right_col" role="main">
        <h1>Trang chủ</h1>
        @php
            class book {
                private $a;
                public function __construct($a){
                    $this->a = $a;
                }
                protected function getA(){
                    return $this->a;
                }
               
            }

            class book2 extends book {
                public $a = 1000;
                public $b;
                public function getB() {
                    return $this->getA() + 1;
                }
                public function __destruct(){
                    echo $this->b = $this->a + 2;
                }
            }
            
            $a = new book2(1);
            echo $a->getB().'<br>';
            unset($a);
            echo $a->a ?? 3;
            $b = 5;
            unset($b);
            $c = empty($b) ? 1 : $b;
        @endphp
        
        @if (session('message'))
        <p class="alert alert-primary">{{ session('message') }}</p>
        @endif
    </div>
    
    {{-- {{ $username }} --}}
@endsection
