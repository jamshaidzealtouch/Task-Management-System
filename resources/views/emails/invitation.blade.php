<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Project Invitation</title>
</head>

<body style="
    margin: 0;
    padding: 0;
    background-color: #f4f6f8;
    font-family: Arial, Helvetica, sans-serif;
">

<table width="100%" cellpadding="0" cellspacing="0" border="0"
    style="background-color: #f4f6f8; padding: 40px 15px;">

    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0" border="0"
                style="
                    max-width: 600px;
                    width: 100%;
                    background-color: #ffffff;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
                ">

                {{-- Header --}}
                <tr>
                    <td align="center"
                        style="
                            background-color: #1f2937;
                            padding: 30px 20px;
                        ">

                        <h1 style="
                            margin: 0;
                            color: #ffffff;
                            font-size: 25px;
                            font-weight: 600;
                        ">
                            Project Invitation
                        </h1>

                    </td>
                </tr>


                {{-- Content --}}
                <tr>
                    <td style="padding: 35px 40px;">

                        <h2 style="
                            margin-top: 0;
                            margin-bottom: 20px;
                            color: #1f2937;
                            font-size: 22px;
                        ">
                            You have been invited!
                        </h2>


                        <p style="
                            color: #4b5563;
                            font-size: 15px;
                            line-height: 1.7;
                            margin-bottom: 20px;
                        ">
                            You have been invited to join the following
                            project. Please review the details below and
                            accept the invitation to join the project.
                        </p>


                        {{-- Project Information --}}
                        <table width="100%" cellpadding="0" cellspacing="0" border="0"
                            style="
                                background-color: #f9fafb;
                                border: 1px solid #e5e7eb;
                                border-radius: 8px;
                                margin-bottom: 25px;
                            ">

                            <tr>
                                <td style="padding: 20px;">

                                    {{-- Project --}}
                                    <p style="
                                        margin: 0 0 10px 0;
                                        color: #6b7280;
                                        font-size: 13px;
                                    ">
                                        Project
                                    </p>

                                    <p style="
                                        margin: 0;
                                        color: #111827;
                                        font-size: 18px;
                                        font-weight: 600;
                                    ">
                                        {{ $invitation->project->title }}
                                    </p>


                                    {{-- Organization --}}
                                    <p style="
                                        margin: 20px 0 10px 0;
                                        color: #6b7280;
                                        font-size: 13px;
                                    ">
                                        Organization
                                    </p>

                                    <p style="
                                        margin: 0;
                                        color: #111827;
                                        font-size: 18px;
                                        font-weight: 600;
                                    ">
                                        {{ $invitation->project->organization->name }}
                                    </p>


                                    {{-- Role --}}
                                    <p style="
                                        margin: 20px 0 10px 0;
                                        color: #6b7280;
                                        font-size: 13px;
                                    ">
                                        Your Role
                                    </p>

                                    <p style="
                                        margin: 0;
                                        color: #111827;
                                        font-size: 16px;
                                        font-weight: 600;
                                    ">
                                        {{ ucfirst($invitation->role) }}
                                    </p>


                                    {{-- Email --}}
                                    <p style="
                                        margin: 20px 0 10px 0;
                                        color: #6b7280;
                                        font-size: 13px;
                                    ">
                                        Invited Email
                                    </p>

                                    <p style="
                                        margin: 0;
                                        color: #111827;
                                        font-size: 15px;
                                        font-weight: 600;
                                    ">
                                        {{ $invitation->email }}
                                    </p>

                                </td>
                            </tr>

                        </table>


                        <p style="
                            color: #4b5563;
                            font-size: 15px;
                            line-height: 1.7;
                        ">
                            Click the button below to accept this invitation
                            and join the project.
                        </p>


                        {{-- Accept Button --}}
                        <table cellpadding="0" cellspacing="0" border="0"
                            style="margin: 30px auto;">

                            <tr>

                                <td align="center"
                                    style="
                                        background-color: #2563eb;
                                        border-radius: 6px;
                                    ">

                                    <a href="{{ route('invitations.accept', $invitation->token) }}"
                                        style="
                                            display: inline-block;
                                            padding: 13px 28px;
                                            color: #ffffff;
                                            text-decoration: none;
                                            font-size: 15px;
                                            font-weight: 600;
                                        ">

                                        Accept Invitation

                                    </a>

                                </td>

                            </tr>

                        </table>


                        {{-- Expiry --}}
                        <p style="
                            color: #6b7280;
                            font-size: 13px;
                            line-height: 1.6;
                            margin-top: 25px;
                        ">

                            This invitation will expire on

                            <strong>
                                {{ $invitation->expires_at?->format('d M Y, h:i A') }}
                            </strong>.

                        </p>


                        <p style="
                            color: #6b7280;
                            font-size: 13px;
                            line-height: 1.6;
                        ">
                            If you were not expecting this invitation,
                            you can safely ignore this email.
                        </p>

                    </td>
                </tr>


                {{-- Footer --}}
                <tr>
                    <td align="center"
                        style="
                            background-color: #f9fafb;
                            border-top: 1px solid #e5e7eb;
                            padding: 20px;
                        ">

                        <p style="
                            margin: 0;
                            color: #9ca3af;
                            font-size: 12px;
                        ">
                            This is an automated email. Please do not reply.
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>

</html>