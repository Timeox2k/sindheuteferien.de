<style {{ $attributes }}>
    *, *::before, *::after {
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8fafc;
        color: #1e293b;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
    }

    /* Header */
    header {
        background-color: #1e3a8a;
        color: #ffffff;
        border-bottom: 3px solid #1d4ed8;
    }

    header .top-bar {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0.6rem 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    header .site-brand {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #ffffff;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.05rem;
        letter-spacing: -0.01em;
        white-space: nowrap;
    }

    header .site-brand:hover {
        color: #dbeafe;
    }

    header .site-brand .site-logo {
        border-radius: 5px;
        display: block;
        flex-shrink: 0;
    }

    header .brand-tld {
        color: #93c5fd;
    }

    header .top-bar-back {
        color: #dbeafe;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-block;
        white-space: nowrap;
    }

    header .top-bar-back:hover {
        color: #ffffff;
        text-decoration: underline;
    }

    header .header-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem 1.25rem 2.25rem;
    }

    header h1 {
        font-size: 2rem;
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        letter-spacing: -0.01em;
        line-height: 1.25;
        color: #ffffff;
    }

    header p {
        font-size: 1.05rem;
        margin: 0;
        color: #bfdbfe;
        line-height: 1.5;
        max-width: 800px;
    }

    /* Sub-Navigation (Alte Bundesländer-Reihe) */
    .navbar {
        background-color: #0f172a;
        border-bottom: 1px solid #1e293b;
        padding: 0.5rem 1rem;
    }

    .navbar ul {
        list-style: none;
        padding: 0;
        margin: 0 auto;
        max-width: 1000px;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem 0.75rem;
        justify-content: center;
    }

    .navbar a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.2rem 0.4rem;
        border-radius: 3px;
        transition: color 0.15s ease, background-color 0.15s ease;
    }

    .navbar a:hover {
        color: #ffffff;
        background-color: #1e293b;
    }

    .navbar a.active {
        color: #ffffff;
        background-color: #2563eb;
    }

    /* Main Container */
    main {
        max-width: 1000px;
        margin: 1.75rem auto 3.5rem;
        padding: 0 1.25rem;
    }

    /* Breadcrumbs */
    .breadcrumbs {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .breadcrumbs a {
        color: #1d4ed8;
        text-decoration: none;
    }

    .breadcrumbs a:hover {
        text-decoration: underline;
    }

    .breadcrumbs .separator {
        color: #94a3b8;
        font-size: 0.75rem;
    }

    .breadcrumbs .current {
        color: #334155;
        font-weight: 600;
    }

    /* Solid Panels / Cards */
    .panel {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 1.5rem;
        margin-bottom: 1.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .panel-header {
        margin-top: 0;
        margin-bottom: 1rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        padding-bottom: 0.6rem;
        border-bottom: 1px solid #e2e8f0;
    }

    /* Status Box */
    .status-box {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        margin-bottom: 1.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .status-bar-header {
        padding: 0.85rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background-color: #f1f5f9;
        color: #475569;
        border-bottom: 1px solid #cbd5e1;
    }

    .status-body {
        padding: 1.5rem;
    }

    .status-banner {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 4px;
        margin-bottom: 1.25rem;
        border: 1px solid transparent;
    }

    .status-banner.is-holiday {
        background-color: #ecfdf5;
        border-color: #a7f3d0;
        color: #065f46;
    }

    .status-banner.is-not-holiday {
        background-color: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }

    .status-banner-badge {
        display: inline-block;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.25rem 0.6rem;
        border-radius: 3px;
        letter-spacing: 0.03em;
        white-space: nowrap;
    }

    .status-banner.is-holiday .status-banner-badge {
        background-color: #059669;
        color: #ffffff;
    }

    .status-banner.is-not-holiday .status-banner-badge {
        background-color: #dc2626;
        color: #ffffff;
    }

    .status-banner-text {
        font-size: 1.15rem;
        font-weight: 600;
        margin: 0;
    }

    /* Next Holiday Notice */
    .next-holiday-notice {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 1.1rem 1.25rem;
        margin-top: 1rem;
    }

    .next-holiday-notice h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
        color: #1e3a8a;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .next-holiday-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        align-items: baseline;
        margin: 0.5rem 0;
    }

    .next-holiday-details .item {
        font-size: 0.95rem;
        color: #334155;
    }

    .next-holiday-details strong {
        color: #0f172a;
        font-weight: 600;
    }

    .countdown-tag {
        display: inline-block;
        background-color: #1e3a8a;
        color: #ffffff;
        padding: 0.2rem 0.5rem;
        border-radius: 3px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    /* Tables */
    .table-container {
        overflow-x: auto;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        margin: 1rem 0 1.5rem;
    }

    .holiday-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.9rem;
    }

    .holiday-table th {
        background-color: #f1f5f9;
        color: #334155;
        font-weight: 700;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid #cbd5e1;
        white-space: nowrap;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .holiday-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        color: #1e293b;
    }

    .holiday-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .holiday-table tbody tr:hover {
        background-color: #eff6ff;
    }

    .holiday-table tbody tr:last-child td {
        border-bottom: none;
    }

    .holiday-table a {
        color: #1d4ed8;
        font-weight: 600;
        text-decoration: none;
    }

    .holiday-table a:hover {
        text-decoration: underline;
    }

    /* Badges */
    .status-pill {
        display: inline-block;
        padding: 0.15rem 0.5rem;
        border-radius: 3px;
        font-size: 0.775rem;
        font-weight: 600;
        white-space: nowrap;
        border: 1px solid transparent;
    }

    .status-pill.active {
        background-color: #ecfdf5;
        color: #065f46;
        border-color: #a7f3d0;
    }

    .status-pill.upcoming {
        background-color: #eff6ff;
        color: #1e40af;
        border-color: #bfdbfe;
    }

    .status-pill.past {
        background-color: #f1f5f9;
        color: #64748b;
        border-color: #e2e8f0;
    }

    /* Key Facts Table / Grid */
    .facts-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0 1.5rem;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        background-color: #ffffff;
    }

    .facts-table td, .facts-table th {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.95rem;
    }

    .facts-table tr:last-child td, .facts-table tr:last-child th {
        border-bottom: none;
    }

    .facts-table th {
        width: 35%;
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        text-align: left;
    }

    .facts-table td {
        font-weight: 600;
        color: #0f172a;
    }

    /* State Grid */
    .state-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
        gap: 0.85rem;
        margin: 1.25rem 0;
    }

    .state-card {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 0.85rem 1rem;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: border-color 0.15s ease, background-color 0.15s ease;
    }

    .state-card:hover {
        border-color: #1d4ed8;
        background-color: #f8fafc;
    }

    .state-card h3 {
        margin: 0 0 0.5rem 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e3a8a;
    }

    .state-card .status-indicator {
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0;
    }

    .state-card .status-indicator.yes {
        color: #059669;
    }

    .state-card .status-indicator.no {
        color: #dc2626;
    }

    /* FAQ */
    .faq-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin: 1rem 0;
    }

    .faq-card {
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        background-color: #ffffff;
        overflow: hidden;
    }

    .faq-card summary {
        padding: 0.85rem 1.1rem;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        background-color: #f8fafc;
        color: #0f172a;
        list-style-position: inside;
    }

    .faq-card summary:hover {
        background-color: #f1f5f9;
        color: #1d4ed8;
    }

    .faq-card .faq-content {
        padding: 1rem 1.1rem;
        border-top: 1px solid #e2e8f0;
        color: #334155;
        font-size: 0.925rem;
        line-height: 1.6;
        background-color: #ffffff;
    }

    .faq-card .faq-content p {
        margin: 0.5rem 0;
    }

    .faq-card .faq-content p:first-child {
        margin-top: 0;
    }

    .faq-card .faq-content p:last-child {
        margin-bottom: 0;
    }

    /* Links */
    a.action-link {
        color: #1d4ed8;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-block;
        margin-top: 0.5rem;
    }

    a.action-link:hover {
        text-decoration: underline;
    }

    /* Footer */
    footer {
        background-color: #ffffff;
        border-top: 1px solid #cbd5e1;
        padding: 2rem 1.25rem;
        text-align: center;
        font-size: 0.85rem;
        color: #64748b;
    }

    footer a {
        color: #334155;
        text-decoration: none;
        margin: 0 0.5rem;
    }

    footer a:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    @media (max-width: 640px) {
        header h1 {
            font-size: 1.6rem;
        }

        header p {
            font-size: 0.95rem;
        }

        header .top-bar {
            padding: 0.5rem 1rem;
        }

        header .site-brand {
            font-size: 0.95rem;
        }

        header .site-brand .site-logo {
            width: 22px;
            height: 22px;
        }

        header .top-bar-back {
            font-size: 0.8rem;
        }

        .navbar {
            padding: 0.4rem 0.5rem;
        }

        .navbar ul {
            gap: 0.35rem 0.5rem;
        }

        .navbar a {
            font-size: 0.75rem;
            padding: 0.15rem 0.35rem;
        }

        .status-banner {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .next-holiday-details {
            flex-direction: column;
            gap: 0.4rem;
        }

        /* Facts table mobile layout */
        .facts-table,
        .facts-table tbody,
        .facts-table tr,
        .facts-table th,
        .facts-table td {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }

        .facts-table tr {
            padding: 0.65rem 0.85rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .facts-table tr:last-child {
            border-bottom: none;
        }

        .facts-table th {
            padding: 0 0 0.2rem 0;
            border: none;
            font-size: 0.775rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #64748b;
            font-weight: 700;
            width: auto;
        }

        .facts-table td {
            padding: 0;
            border: none;
            font-size: 0.95rem;
            color: #0f172a;
        }

        /* Mobile Card Layout for Holiday Tables */
        .table-container {
            border: none;
            background: transparent;
            margin: 0.75rem 0 1rem;
            overflow-x: visible;
        }

        .holiday-table,
        .holiday-table tbody,
        .holiday-table tr,
        .holiday-table td {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }

        .holiday-table thead {
            display: none;
        }

        .holiday-table tbody tr {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin-bottom: 0.75rem;
            padding: 0.85rem 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .holiday-table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .holiday-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .holiday-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.4rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            text-align: right;
        }

        .holiday-table td:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        /* Title / Primary cell at top of each card */
        .holiday-table td:first-child {
            font-size: 1.05rem;
            font-weight: 700;
            padding-top: 0;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 0.35rem;
            text-align: left;
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .holiday-table td:first-child::before {
            display: none !important;
        }

        .holiday-table td[data-label]::before {
            content: attr(data-label);
            font-weight: 600;
            color: #64748b;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            text-align: left;
            margin-right: 1rem;
            flex-shrink: 0;
        }
    }

    .hidden-md-block {
        display: none !important;
    }

    @media (min-width: 692px) {
        .hidden-md-block {
            display: block !important;
        }
    }

    .block-md-none {
        display: block !important;
    }

    @media (min-width: 692px) {
        .block-md-none {
            display: none !important;
        }
    }

    .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
</style>
