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

<div class="row marketing">
    <div class="collg6">
        <h4>Subheading</h4>
        <p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>

        <h4>Subheading</h4>
        <p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.</p>

        <h4>Subheading</h4>
        <p>Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
    </div>

    <div class="collg6">
        <h4>Subheading</h4>
        <p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>

        <h4>Subheading</h4>
        <p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.</p>

        <h4>Subheading</h4>
        <p>Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
    </div>
</div>