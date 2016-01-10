<?php
/**
 * Created by DP-Webtechnics.
 * Rights are property of DP-Webtechnics
 */

namespace Loxodo\App\Route;


use Loxodo\App\Request\Request;

interface RestControllerInterface
{

    public function index(Request $request);
    public function view(Request $request, $id);
    public function edit(Request $request, $id);
    public function add(Request $request);
    public function store(Request $request);
    public function update(Request $request, $id);
    public function delete(Request $request, $id);

}