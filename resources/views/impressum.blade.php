@php use App\Http\Controllers\SchoolHolidayController; @endphp
    <!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description"
          content="Sind heute Ferien? Finde heraus, in welchen deutschen Bundesländern heute Schulferien sind. Einfach, aktuell und übersichtlich."/>
    <meta name="keywords"
          content="Ferien heute, Schulferien, Bundesländer, Deutschland, Ferienkalender, Ferienübersicht"/>
    <meta name="author" content="SindHeuteFerien.de"/>
    <title>Impressum</title>
    <x-parts.style />
</head>
<body>
<main>

    <section class="strong-services-wrapper section-padding fw500" id="impressum">
        <div class="container">
            <h1>Impressum</h1>
            <h2>Informationen nach § 5 TMG</h2>
            <p>Tiziano Santo Metzler<br/> Bautzener Allee 59<br/> 02977 Hoyerswerda <br>Deutschland</p>
            <h2>Contact</h2>
            <p>Telefon: +49 1590 1084284<br/>
                E-Mail: hoy-metzler-it@fn.de</p>
            <h2>Haftung für Inhalte</h2>
            <p>Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den
                allgemeinen
                Gesetzen
                verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet,
                übermittelte
                oder
                gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine
                rechtswidrige
                Tätigkeit hinweisen.</p>
            <p>Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen
                bleiben
                hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer
                konkreten
                Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese
                Inhalte
                umgehend
                entfernen.</p>
            <h2>Haftung für Links</h2>
            <p>Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben.
                Deshalb
                können wir für diese fremden Inhalte auch keine Gewähr übernehmen.
                Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten
                verantwortlich.
                Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft.
                Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar.
                Eine permanente inhaltliche Kontrolle der
                der verlinkten Seiten ist ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar.
                Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.
            </p>
        </div>
    </section>

</main>
<footer>
    &copy; 2025 SindHeuteFerien.de – Alle Angaben ohne Gewähr. Datenquelle: <a href="https://www.schulferien.org"
                                                                               target="_blank"
                                                                               rel="noopener noreferrer">schulferien.org</a>

    <a href="{{ route('impressum') }}">Impressum</a>

</footer>
</body>
</html>
