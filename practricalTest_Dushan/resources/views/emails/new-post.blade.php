<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post Notification</title>
</head>
<body style="margin:0;background-color:#f4efe6;font-family:Arial,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#fffdf9;border-radius:20px;overflow:hidden;border:1px solid #f1e4cf;">
                    <tr>
                        <td style="padding:32px;background:linear-gradient(135deg,#7c2d12 0%,#c2410c 100%);color:#fff7ed;">
                            <p style="margin:0 0 8px;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;">New Post Alert</p>
                            <h1 style="margin:0;font-size:28px;line-height:1.3;">{{ $post->title }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.8;color:#4b5563;">
                                A new post was published on <strong>{{ $post->website->name }}</strong>.
                            </p>
                            <div style="padding:20px;border-radius:16px;background:#fff7ed;border:1px solid #fed7aa;">
                                <p style="margin:0;font-size:15px;line-height:1.8;color:#374151;">
                                    {{ $post->description }}
                                </p>
                            </div>
                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin:24px 0 20px;">
                                <tr>
                                    <td style="border-radius:999px;background:#b45309;">
                                        <a href="{{ $post->url }}" style="display:inline-block;padding:14px 22px;color:#fffdf8;text-decoration:none;font-size:14px;font-weight:bold;">
                                            Read this post
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 8px;font-size:13px;color:#6b7280;">
                                Website: <a href="{{ $post->website->url }}" style="color:#9a3412;text-decoration:none;">{{ $post->website->name }}</a>
                            </p>
                            <p style="margin:0;font-size:13px;color:#9ca3af;">
                                Post URL: {{ $post->url }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
