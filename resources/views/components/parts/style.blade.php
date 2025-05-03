<style {{ $attributes }}>
    body {
        font-family: 'Helvetica', sans-serif;
        margin: 0;
        padding: 0;
        background: #f9f9f9;
        color: #111;
    }

    header {
        background-color: #1e40af;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    header .top-bar {
        width: 100%;
        max-width: 900px;
    }

    header .top-bar a {
        color: #fff;
        display: inline-block;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        white-space: nowrap;
        text-align: center;
    }

    header .top-bar a:hover {
        text-decoration: underline;
    }

    header .header-container {
        padding: 2rem 1rem;
        text-align: center;
    }

    header h1 {
        font-size: 2.5rem;
        margin: 0.5rem;
    }

    header p {
        font-size: 1.25rem;
    }

    main {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .info {
        text-align: center;
        margin-bottom: 2rem;
        color: #333;
    }

    .info p {
        font-size: 1.1rem;
    }

    .state-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .state {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: start;
        text-align: center;
        flex: 1;
    }

    .state p:last-child {
        margin-bottom: 0;
        margin-top: auto;
    }

    .state h3 {
        margin: auto 0;
    }

    .holiday-yes, .holiday-no {
        margin-top: auto;
    }

    .holiday-yes {
        color: #0c5928;
        font-weight: bold;
    }

    .holiday-no {
        color: #dc2626;
        font-weight: bold;
    }

    .holiday-yes span.highlight {
        color: #0c5928;
    }

    .holiday-no span.highlight {
        color: #a81b1b;
    }

    span.muted, p.muted {
        color: #666;
        font-size: 0.9rem;
        font-weight: normal;
    }

    footer {
        text-align: center;
        padding: 2rem 1rem;
        color: #666;
        font-size: 0.9rem;
    }

    @media (max-width: 600px) {
        header h1 {
            font-size: 2rem;
        }

        .info p {
            font-size: 1rem;
        }
    }

    .state.state-sm {
        flex: 1 1 100%;
    }

    .state.state-sm > * {
        flex: 1;
        padding: 0.25rem;
    }

    .state a {
        color: #1a202c;
        text-decoration: none;
        transition: color 0.2s;
    }

    .state a:hover {
        color: #1e40af;
        text-decoration: underline;
    }

    .state:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .navbar {
        background-color: #1e40af;
        padding: 1rem;
        text-align: center;
    }

    .navbar ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .navbar li {
        display: inline-block;
        margin-right: 1rem;
    }

    .navbar a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
    }

    .navbar a:hover {
        text-decoration: underline;
    }

    /** Mobile, tablet, hidden */
    @media (max-width: 600px) {
        .navbar {
            display: none;
        }
    }

    .info {
        margin-bottom: 2rem;
        padding: 1rem;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 1rem;
        color: #1e40af;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .holiday-calendar {
        margin: 2rem 0;
    }

    footer {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        gap: 1rem;
    }

    .hidden-md-block {
        display: none !important;;
    }

    @media (min-width: 692px) {
        .hidden-md-block {
            display: block !important;
        }
        .hidden-md-inline-block {
            display: inline-block !important;;
        }
    }

    .block-md-none {
        display: block !important;;
    }

    @media (min-width: 692px) {
        .block-md-none {
            display: none !important;;
        }
    }
</style>
