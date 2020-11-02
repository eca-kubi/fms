<?php

require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

sendMail();

function setCurlOptions(SendMailRequest $request, string $accessToken)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://graph.microsoft.com/v1.0/users/163bbe6b-8953-43d3-acc8-4f615efada89/sendMail",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($request),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"]
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function getUnsentEmail()
{
    $db = Database::getDbh();
    try {
        return $db->where('sent', false)->get('emails');
    } catch (Exception $e) {
    }
    return [];
}

function sendMail() {
    $accessToken = (new AccessToken())->access_token;
   // $expiredAccessToken = 'eyJ0eXAiOiJKV1QiLCJub25jZSI6IjNST09tYm1XOGlCY0dNSEVWeWUyV3BhSjJYVkxTWEJrVzRvVElIdzhtNEkiLCJhbGciOiJSUzI1NiIsIng1dCI6ImtnMkxZczJUMENUaklmajRydDZKSXluZW4zOCIsImtpZCI6ImtnMkxZczJUMENUaklmajRydDZKSXluZW4zOCJ9.eyJhdWQiOiIwMDAwMDAwMy0wMDAwLTAwMDAtYzAwMC0wMDAwMDAwMDAwMDAiLCJpc3MiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC8zYTY1MmNhYy1jNzRmLTRiZDctOTE0NC1hOTM3YWZjNWI1NDEvIiwiaWF0IjoxNjA0MTg1MTA4LCJuYmYiOjE2MDQxODUxMDgsImV4cCI6MTYwNDE4OTAwOCwiYWlvIjoiRTJSZ1lIRFlsL1VuOEUrOEVQK2tyczdxSTU1bkFRPT0iLCJhcHBfZGlzcGxheW5hbWUiOiJBZGFtdXMgQXBwcyIsImFwcGlkIjoiYjcyMjA1YjAtODg4Mi00YmIwLTk2YjgtMzg2MTMyZWZiZWFiIiwiYXBwaWRhY3IiOiIxIiwiaWRwIjoiaHR0cHM6Ly9zdHMud2luZG93cy5uZXQvM2E2NTJjYWMtYzc0Zi00YmQ3LTkxNDQtYTkzN2FmYzViNTQxLyIsImlkdHlwIjoiYXBwIiwib2lkIjoiYzNkNzNkZTMtY2Y2Yi00YzZlLWFmZDQtNjJmYWVmMTVkMWE3IiwicmgiOiIwLkFBQUFyQ3hsT2tfSDEwdVJSS2szcjhXMVFiQUZJcmVDaUxCTGxyZzRZVEx2dnF0ekFBQS4iLCJyb2xlcyI6WyJNYWlsLlJlYWRXcml0ZSIsIlVzZXIuUmVhZFdyaXRlLkFsbCIsIk1haWwuUmVhZEJhc2ljLkFsbCIsIkRpcmVjdG9yeS5SZWFkV3JpdGUuQWxsIiwiTWFpbGJveFNldHRpbmdzLlJlYWQiLCJEaXJlY3RvcnkuUmVhZC5BbGwiLCJVc2VyLlJlYWQuQWxsIiwiTWFpbC5SZWFkIiwiTWFpbC5TZW5kIiwiTWFpbGJveFNldHRpbmdzLlJlYWRXcml0ZSIsIk1haWwuUmVhZEJhc2ljIl0sInN1YiI6ImMzZDczZGUzLWNmNmItNGM2ZS1hZmQ0LTYyZmFlZjE1ZDFhNyIsInRlbmFudF9yZWdpb25fc2NvcGUiOiJBRiIsInRpZCI6IjNhNjUyY2FjLWM3NGYtNGJkNy05MTQ0LWE5MzdhZmM1YjU0MSIsInV0aSI6Ik1mWktoVUs0dFU2ZFJEVURLb3cyQUEiLCJ2ZXIiOiIxLjAiLCJ4bXNfdGNkdCI6MTU5OTY2NDM5NH0.gfT9VW66zkwim6DpfmWlnr7F0JRJ2dFUAMDycNLFBQZ-84ZlCF40tP3RjdLAp2QXaQ2aaGhP7oXiEHiiYzdwRlsHNWZNZYhEauyxAvotkZ6W4kCDsVeNr6QT5WR56Razkouuc96wZDeTzUWAYe9JR6lik1u3T9aIY6hXm5KbEvz0dEQ35Zu2UVuu_EJCnYLyBS7oTYZBThGgtQ7d9SbNlBjXxS-FzDCpEHTfMgoQHLCp3STm3pbihnT8FuqcEzXilt6LqeY2FioFwsQqmC4JxXh_9PAu8SoMAnGaLbrnDKfZW8rddBOG7XR8zmzG6wwcQpq5Ibm0Vhdin31m1HWCzg';
    while (true) {
        $unsent = EmailCollection::createFromArrayValues(getUnsentEmail());
        foreach ($unsent->getEmails() as $email) {
            $body = new Body('HTML', $email->content);
            $message = new Message($email->subject, $body);
            $message->toRecipients = [new Recipient(new EmailAddress($email->recipient_address))];
            $request = new SendMailRequest($message);
            $request->saveToSentItems = true;
            $response = json_decode(setCurlOptions($request, $accessToken));
            if ($response) {
                if ($response->error->message == "Access token has expired.") {
                    $accessToken = (new AccessToken())->access_token;
                    break;
                }
            } else {
                $email_model = new EmailDbModel((array)$email);
                $email_model->sent = true;
                $email_model->update();
            }
        }
        sleep(10);
    }
}
