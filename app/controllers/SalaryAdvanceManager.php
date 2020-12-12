<?php

class SalaryAdvanceManager extends Controller
{
    public function index($request_number = null): void
    {
       redirect('salary-advance/single-requests/' . $request_number );
    }
}
