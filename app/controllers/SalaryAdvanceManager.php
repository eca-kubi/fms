<?php

class SalaryAdvanceManager extends Controller
{
    public function index($request_number = null): void
    {
       redirect('salary-advance/single-request/' . $request_number );
    }
}
