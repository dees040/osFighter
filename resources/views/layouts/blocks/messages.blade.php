@if(Session::has('m_success'))
    <div class="alert alert-success" role="alert">
        {{ Session::get('m_success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
    </div>
@endif
@if(Session::has('m_warning'))
    <div class="alert alert-warning" role="alert">
        {{ Session::get('m_warning') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
    </div>
@endif
@if(Session::has('m_danger'))
    <div class="alert alert-danger" role="alert">
        {{ Session::get('m_danger') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
    </div>
@endif