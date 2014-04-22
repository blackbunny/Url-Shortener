<div class="jumbotron">
    {% if token %}
<h1>{{tr(title)}}</h1>
        <p class="lead">
            {{ tr(have_fun_using)}} :)<br />
            <a href="{{url(token)}}" target="_blank">{{url(token)}}</a>
        </p>
{% else %}
    <h1>{{tr(title)}}</h1>
    <form method="post" autocomplete="off" action="{{ url('links/create') }}">
        <p class="lead">
            <input class="form-control input-large" name="longurl" type="text" placeholder="{{url()}}" />
        </p>
        <p><input type="submit" class="btn btn-large btn-success" value="{{tr(shorten_btn)}} » " /></p>
    </form>
    {% endif %}
</div>