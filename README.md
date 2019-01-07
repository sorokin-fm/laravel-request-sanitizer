We all know that the thinner is controller the better it is. And we wanted to have such controllers:

```
    public function update(UpdateRequest $request, $id)
    {
        /** @var SomeModel $model */
        $model = SomeModel::find($id);
        $model->fill($request->input());
        $model->save();

        return back()
            ->with('success', __('Some model has been changed'));
    }
```

Unfortunately, there's a couple of cases, where we just can't directly  pass inputs to fill.

For example, some fields are checkboxes. So we need to do a little pre-processing:
```
$values = $request->input();
$values['checkbox1'] = $values['checkbox1'] ? 1 : 0;
$values['checkbox2'] = $values['checkbox2'] ? 1 : 0;
$model->fill($values);
```

Another scenario is when we need to upload files:

```
$values = $request->input();

$file = $request->file('picture');
if ($file) {
    $filename = Transliteration::make(
        pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        ['type' => 'filename', 'lowercase' => true]);

    $filename .= '.' . $file->getClientOriginalExtension();

    $file->move($filePath, $filename);

    $values['picture'] = $filename;
}

$file = $request->file('thumb');
if ($file) {
    $filename = Transliteration::make(
        pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        ['type' => 'filename', 'lowercase' => true]);

    $filename .= '.' . $file->getClientOriginalExtension();

    $file->move($filePath, $filename);

    $values['thumb'] = $filename;
}

$model->fill($values);
```

As you see, it's not very thin, yes? And the worst - we need to duplicate that code in store and update actions. So what can we do with all of this to make it better?

For - add this package:

```
compose require "sorokin-fm/laravel-request-sanitizer"
```

And then, add it to you request class:

```
use Illuminate\Foundation\Http\FormRequest;
use SorokinFM\RequestSanitizerTrait;

class StoreRequest extends FormRequest

    use RequestSanitizerTrait;
    const SANITIZE_RULES = [
        'enabled' => 'checkbox',
        'picture' => 'file:path/to/store',
    ];

    ...
```

Now you're ready to use it:

```
    public function update(UpdateRequest $request, $id)
    {
        /** @var SomeModel $model */
        $model = SomeModel::find($id);
        $model->fill($request->sanitize());
        $model->save();

        return back()
            ->with('success', __('Some model has been changed'));
    }
```

Of course, if you need to define your own sanitizer, you can implement interface SorokinFM\SanitizerInterface and then specify full class name ( with namespace ) as rule name, for example:

```
    use RequestSanitizerTrait;
    const SANITIZE_RULES = [
        'enabled' => 'checkbox',
        'picture' => 'file:path/to/store',
        'custom_data' => '\App\Sanitizers\CustomRequestSanitizer',
    ];
```

And one more thing. You also can use wildcards at field names. It will be useful at localization, when you don't know all names of field:

```
    use RequestSanitizerTrait;
    const SANITIZE_RULES = [
        'enabled' => 'checkbox',
        'picture:*' => 'file:path/to/store',
        'custom_data' => '\App\Sanitizers\CustomRequestSanitizer',
    ];
```
