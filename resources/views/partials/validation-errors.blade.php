<div style="color: red; border: 1px solid red; padding: 10px; margin: 10px 0; background-color: #ffebee;">
    <h4>Please fix the following errors:</h4>
    <ul>
        @foreach($errors as $field => $fieldErrors)
            @foreach($fieldErrors as $error)
                <li><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong> {{ $error }}</li>
            @endforeach
        @endforeach
    </ul>
</div> 