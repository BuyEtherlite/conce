--
-- PostgreSQL database dump
--

-- Dumped from database version 16.9
-- Dumped by pg_dump version 16.9

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: ap_bills; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ap_bills (
    id bigint NOT NULL,
    bill_number character varying(255) NOT NULL,
    vendor_id bigint NOT NULL,
    vendor_invoice_number character varying(255),
    bill_date date NOT NULL,
    due_date date NOT NULL,
    description text,
    subtotal numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    tax_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    total_amount numeric(15,2) NOT NULL,
    amount_paid numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    balance_due numeric(15,2) NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    created_by bigint NOT NULL,
    approved_by bigint,
    approved_date timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT ap_bills_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'paid'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: ap_bills_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ap_bills_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ap_bills_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ap_bills_id_seq OWNED BY public.ap_bills.id;


--
-- Name: ap_vendors; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ap_vendors (
    id bigint NOT NULL,
    vendor_number character varying(255) NOT NULL,
    vendor_name character varying(255) NOT NULL,
    contact_person character varying(255),
    email character varying(255),
    phone character varying(255),
    address text,
    tax_number character varying(255),
    bank_name character varying(255),
    account_number character varying(255),
    payment_terms character varying(255),
    credit_limit numeric(15,2),
    is_active boolean DEFAULT true NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: ap_vendors_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ap_vendors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ap_vendors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ap_vendors_id_seq OWNED BY public.ap_vendors.id;


--
-- Name: ar_invoices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ar_invoices (
    id bigint NOT NULL,
    customer_id bigint NOT NULL,
    invoice_number character varying(255) NOT NULL,
    invoice_date date NOT NULL,
    due_date date NOT NULL,
    amount numeric(15,2) NOT NULL,
    balance_due numeric(15,2) NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    description text,
    terms character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT ar_invoices_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'sent'::character varying, 'paid'::character varying, 'overdue'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: ar_invoices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ar_invoices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ar_invoices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ar_invoices_id_seq OWNED BY public.ar_invoices.id;


--
-- Name: ar_receipts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ar_receipts (
    id bigint NOT NULL,
    receipt_number character varying(255) NOT NULL,
    customer_id bigint NOT NULL,
    ar_invoice_id bigint,
    receipt_date date NOT NULL,
    amount_received numeric(15,2) NOT NULL,
    payment_method character varying(255) NOT NULL,
    payment_reference character varying(255),
    bank_account_id bigint,
    notes text,
    council_id bigint,
    department_id bigint,
    office_id bigint,
    created_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: ar_receipts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ar_receipts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ar_receipts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ar_receipts_id_seq OWNED BY public.ar_receipts.id;


--
-- Name: asset_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.asset_categories (
    id bigint NOT NULL,
    category_name character varying(255) NOT NULL,
    description text,
    default_useful_life integer,
    depreciation_method character varying(255) DEFAULT 'straight_line'::character varying NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: asset_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.asset_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: asset_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.asset_categories_id_seq OWNED BY public.asset_categories.id;


--
-- Name: asset_depreciation_history; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.asset_depreciation_history (
    id bigint NOT NULL,
    fixed_asset_id bigint NOT NULL,
    depreciation_year integer NOT NULL,
    depreciation_month integer NOT NULL,
    depreciation_amount numeric(15,2) NOT NULL,
    accumulated_depreciation numeric(15,2) NOT NULL,
    book_value numeric(15,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: asset_depreciation_history_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.asset_depreciation_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: asset_depreciation_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.asset_depreciation_history_id_seq OWNED BY public.asset_depreciation_history.id;


--
-- Name: asset_locations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.asset_locations (
    id bigint NOT NULL,
    location_name character varying(255) NOT NULL,
    description text,
    building character varying(255),
    floor character varying(255),
    room character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: asset_locations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.asset_locations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: asset_locations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.asset_locations_id_seq OWNED BY public.asset_locations.id;


--
-- Name: bank_accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bank_accounts (
    id bigint NOT NULL,
    account_number character varying(255) NOT NULL,
    account_name character varying(255) NOT NULL,
    bank_name character varying(255) NOT NULL,
    branch_name character varying(255),
    account_code character varying(255),
    currency_code character varying(3) DEFAULT 'USD'::character varying NOT NULL,
    opening_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    current_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: bank_accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bank_accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bank_accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bank_accounts_id_seq OWNED BY public.bank_accounts.id;


--
-- Name: bank_reconciliations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bank_reconciliations (
    id bigint NOT NULL,
    reconciliation_number character varying(255) NOT NULL,
    bank_account_id bigint NOT NULL,
    bank_statement_id bigint,
    reconciliation_date date NOT NULL,
    statement_date date NOT NULL,
    statement_balance numeric(15,2) NOT NULL,
    book_balance numeric(15,2) NOT NULL,
    outstanding_deposits numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    outstanding_checks numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    bank_charges numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    interest_earned numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    adjusted_balance numeric(15,2) NOT NULL,
    variance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    prepared_by bigint NOT NULL,
    reviewed_by bigint,
    reviewed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT bank_reconciliations_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'in_progress'::character varying, 'reconciled'::character varying, 'discrepancy'::character varying])::text[])))
);


--
-- Name: bank_reconciliations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bank_reconciliations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bank_reconciliations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bank_reconciliations_id_seq OWNED BY public.bank_reconciliations.id;


--
-- Name: bank_statements; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bank_statements (
    id bigint NOT NULL,
    statement_number character varying(255) NOT NULL,
    bank_account_id bigint NOT NULL,
    statement_date date NOT NULL,
    period_start date NOT NULL,
    period_end date NOT NULL,
    opening_balance numeric(15,2) NOT NULL,
    closing_balance numeric(15,2) NOT NULL,
    file_path character varying(255),
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT bank_statements_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'processed'::character varying, 'reconciled'::character varying])::text[])))
);


--
-- Name: bank_statements_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bank_statements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bank_statements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bank_statements_id_seq OWNED BY public.bank_statements.id;


--
-- Name: bill_line_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bill_line_items (
    id bigint NOT NULL,
    bill_id bigint NOT NULL,
    service_id bigint NOT NULL,
    description character varying(255) NOT NULL,
    quantity numeric(10,2) NOT NULL,
    unit_rate numeric(10,2) NOT NULL,
    amount numeric(12,2) NOT NULL,
    tax_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: bill_line_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bill_line_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bill_line_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bill_line_items_id_seq OWNED BY public.bill_line_items.id;


--
-- Name: bill_payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bill_payments (
    id bigint NOT NULL,
    bill_id bigint NOT NULL,
    payment_reference character varying(255) NOT NULL,
    amount numeric(12,2) NOT NULL,
    payment_date date NOT NULL,
    payment_method_id bigint,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT bill_payments_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'completed'::character varying, 'failed'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: bill_payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bill_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bill_payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bill_payments_id_seq OWNED BY public.bill_payments.id;


--
-- Name: bill_reminders; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bill_reminders (
    id bigint NOT NULL,
    bill_id bigint NOT NULL,
    type character varying(255) NOT NULL,
    sent_date date NOT NULL,
    method character varying(255) DEFAULT 'email'::character varying NOT NULL,
    message text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT bill_reminders_type_check CHECK (((type)::text = ANY ((ARRAY['first_reminder'::character varying, 'second_reminder'::character varying, 'final_notice'::character varying])::text[])))
);


--
-- Name: bill_reminders_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bill_reminders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bill_reminders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bill_reminders_id_seq OWNED BY public.bill_reminders.id;


--
-- Name: budgets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.budgets (
    id bigint NOT NULL,
    budget_name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    fiscal_year character varying(255) NOT NULL,
    total_budget numeric(15,2) NOT NULL,
    total_spent numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    variance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    created_by bigint NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT budgets_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'approved'::character varying, 'active'::character varying, 'closed'::character varying])::text[])))
);


--
-- Name: budgets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.budgets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: budgets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.budgets_id_seq OWNED BY public.budgets.id;


--
-- Name: building_inspections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.building_inspections (
    id bigint NOT NULL,
    inspection_number character varying(255) NOT NULL,
    property_id bigint NOT NULL,
    inspection_type character varying(255) NOT NULL,
    inspection_date date NOT NULL,
    inspector_name character varying(255) NOT NULL,
    inspection_stage character varying(255) NOT NULL,
    findings text NOT NULL,
    result character varying(255) NOT NULL,
    defects_noted text,
    recommendations text,
    re_inspection_date date,
    status character varying(255) DEFAULT 'completed'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: building_inspections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.building_inspections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: building_inspections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.building_inspections_id_seq OWNED BY public.building_inspections.id;


--
-- Name: burial_records; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.burial_records (
    id bigint NOT NULL,
    burial_number character varying(255) NOT NULL,
    plot_id bigint NOT NULL,
    deceased_name character varying(255) NOT NULL,
    deceased_id_number character varying(255),
    date_of_birth date,
    date_of_death date NOT NULL,
    burial_date date NOT NULL,
    cause_of_death character varying(255),
    next_of_kin_name character varying(255) NOT NULL,
    next_of_kin_contact character varying(255) NOT NULL,
    undertaker character varying(255),
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: burial_records_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.burial_records_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: burial_records_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.burial_records_id_seq OWNED BY public.burial_records.id;


--
-- Name: cash_transactions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cash_transactions (
    id bigint NOT NULL,
    transaction_type character varying(255) NOT NULL,
    amount numeric(15,2) NOT NULL,
    description text NOT NULL,
    transaction_date date NOT NULL,
    account_id bigint NOT NULL,
    bank_account_id bigint,
    reference_number character varying(255),
    reconciled_at timestamp(0) without time zone,
    reconciliation_id bigint,
    created_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: cash_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cash_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cash_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cash_transactions_id_seq OWNED BY public.cash_transactions.id;


--
-- Name: cashbook_entries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cashbook_entries (
    id bigint NOT NULL,
    entry_number character varying(255) NOT NULL,
    entry_type character varying(255) NOT NULL,
    transaction_date date NOT NULL,
    reference_number character varying(255),
    description text NOT NULL,
    amount numeric(15,2) NOT NULL,
    currency_code character varying(3) NOT NULL,
    exchange_rate numeric(10,6) DEFAULT '1'::numeric NOT NULL,
    payment_method character varying(255) NOT NULL,
    bank_account_id character varying(255),
    account_code character varying(255) NOT NULL,
    created_by bigint NOT NULL,
    status character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT cashbook_entries_entry_type_check CHECK (((entry_type)::text = ANY (ARRAY[('receipt'::character varying)::text, ('payment'::character varying)::text]))),
    CONSTRAINT cashbook_entries_payment_method_check CHECK (((payment_method)::text = ANY (ARRAY[('cash'::character varying)::text, ('cheque'::character varying)::text, ('electronic'::character varying)::text, ('mobile'::character varying)::text]))),
    CONSTRAINT cashbook_entries_status_check CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('cleared'::character varying)::text, ('cancelled'::character varying)::text])))
);


--
-- Name: cashbook_entries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cashbook_entries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cashbook_entries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cashbook_entries_id_seq OWNED BY public.cashbook_entries.id;


--
-- Name: cemeteries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cemeteries (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    location character varying(255) NOT NULL,
    address text NOT NULL,
    total_area numeric(10,2) NOT NULL,
    total_plots integer NOT NULL,
    available_plots integer NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    sections json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: cemeteries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cemeteries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cemeteries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cemeteries_id_seq OWNED BY public.cemeteries.id;


--
-- Name: cemetery_plots; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cemetery_plots (
    id bigint NOT NULL,
    cemetery_id bigint NOT NULL,
    plot_number character varying(255) NOT NULL,
    section character varying(255) NOT NULL,
    row_number character varying(255) NOT NULL,
    plot_type character varying(255) NOT NULL,
    size_length numeric(8,2) NOT NULL,
    size_width numeric(8,2) NOT NULL,
    price numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'available'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: cemetery_plots_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cemetery_plots_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cemetery_plots_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cemetery_plots_id_seq OWNED BY public.cemetery_plots.id;


--
-- Name: committee_committees; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.committee_committees (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    type character varying(255) NOT NULL,
    established_date date NOT NULL,
    dissolution_date date,
    chairperson character varying(255) NOT NULL,
    secretary character varying(255) NOT NULL,
    meeting_schedule json,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: committee_committees_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.committee_committees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: committee_committees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.committee_committees_id_seq OWNED BY public.committee_committees.id;


--
-- Name: committee_meetings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.committee_meetings (
    id bigint NOT NULL,
    committee_id bigint NOT NULL,
    meeting_number character varying(255) NOT NULL,
    meeting_date date NOT NULL,
    start_time time(0) without time zone NOT NULL,
    end_time time(0) without time zone,
    venue character varying(255) NOT NULL,
    meeting_type character varying(255) NOT NULL,
    agenda text,
    minutes text,
    status character varying(255) DEFAULT 'scheduled'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: committee_meetings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.committee_meetings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: committee_meetings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.committee_meetings_id_seq OWNED BY public.committee_meetings.id;


--
-- Name: communications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.communications (
    id bigint NOT NULL,
    type character varying(255) NOT NULL,
    subject character varying(255),
    message text NOT NULL,
    sender character varying(255) NOT NULL,
    recipient character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    sent_at timestamp(0) without time zone,
    metadata json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: communications_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.communications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: communications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.communications_id_seq OWNED BY public.communications.id;


--
-- Name: cost_centers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cost_centers (
    id bigint NOT NULL,
    cost_center_code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    manager_id bigint,
    budget_allocation numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: cost_centers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cost_centers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cost_centers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cost_centers_id_seq OWNED BY public.cost_centers.id;


--
-- Name: councils; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.councils (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    province character varying(255) NOT NULL,
    region character varying(255) NOT NULL,
    address text,
    phone character varying(255),
    email character varying(255),
    website character varying(255),
    established_date date,
    mayor_name character varying(255),
    population integer,
    area_km2 numeric(10,2),
    contact_info text,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: councils_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.councils_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: councils_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.councils_id_seq OWNED BY public.councils.id;


--
-- Name: currencies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.currencies (
    id bigint NOT NULL,
    currency_code character varying(3) NOT NULL,
    currency_name character varying(255) NOT NULL,
    currency_symbol character varying(10) NOT NULL,
    exchange_rate numeric(10,6) NOT NULL,
    is_base_currency boolean DEFAULT false NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    decimal_places integer DEFAULT 2 NOT NULL,
    rounding_precision numeric(8,6) DEFAULT 0.01 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: currencies_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.currencies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: currencies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.currencies_id_seq OWNED BY public.currencies.id;


--
-- Name: customer_accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.customer_accounts (
    id bigint NOT NULL,
    account_number character varying(255) NOT NULL,
    account_type character varying(255) NOT NULL,
    customer_name character varying(255) NOT NULL,
    contact_person character varying(255),
    phone character varying(255),
    email character varying(255),
    physical_address text NOT NULL,
    postal_address text,
    id_number character varying(255),
    vat_number character varying(255),
    credit_limit numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    current_balance numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    council_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT customer_accounts_account_type_check CHECK (((account_type)::text = ANY ((ARRAY['individual'::character varying, 'business'::character varying, 'organization'::character varying])::text[])))
);


--
-- Name: customer_accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.customer_accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: customer_accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.customer_accounts_id_seq OWNED BY public.customer_accounts.id;


--
-- Name: customers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.customers (
    id bigint NOT NULL,
    customer_number character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255),
    phone character varying(255),
    address text,
    id_number character varying(255),
    date_of_birth date,
    gender character varying(255),
    customer_type character varying(255) DEFAULT 'individual'::character varying NOT NULL,
    business_registration_number character varying(255),
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT customers_customer_type_check CHECK (((customer_type)::text = ANY (ARRAY[('individual'::character varying)::text, ('business'::character varying)::text]))),
    CONSTRAINT customers_gender_check CHECK (((gender)::text = ANY (ARRAY[('male'::character varying)::text, ('female'::character varying)::text, ('other'::character varying)::text])))
);


--
-- Name: customers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.customers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: customers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.customers_id_seq OWNED BY public.customers.id;


--
-- Name: debtor_transactions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.debtor_transactions (
    id bigint NOT NULL,
    debtor_id bigint NOT NULL,
    transaction_number character varying(255) NOT NULL,
    transaction_type character varying(255) NOT NULL,
    transaction_date date NOT NULL,
    due_date date,
    description text NOT NULL,
    amount numeric(15,2) NOT NULL,
    currency_code character varying(3) NOT NULL,
    reference_number character varying(255),
    status character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT debtor_transactions_status_check CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('paid'::character varying)::text, ('overdue'::character varying)::text, ('written_off'::character varying)::text]))),
    CONSTRAINT debtor_transactions_transaction_type_check CHECK (((transaction_type)::text = ANY (ARRAY[('invoice'::character varying)::text, ('payment'::character varying)::text, ('credit_note'::character varying)::text, ('adjustment'::character varying)::text])))
);


--
-- Name: debtor_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.debtor_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: debtor_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.debtor_transactions_id_seq OWNED BY public.debtor_transactions.id;


--
-- Name: debtors; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.debtors (
    id bigint NOT NULL,
    debtor_number character varying(255) NOT NULL,
    debtor_name character varying(255) NOT NULL,
    debtor_type character varying(255) NOT NULL,
    contact_person character varying(255),
    phone character varying(255),
    email character varying(255),
    address text,
    credit_limit numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    payment_terms integer DEFAULT 30 NOT NULL,
    current_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: debtors_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.debtors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: debtors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.debtors_id_seq OWNED BY public.debtors.id;


--
-- Name: departments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.departments (
    id bigint NOT NULL,
    council_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    head_name character varying(255),
    phone character varying(255),
    email character varying(255),
    module_access json,
    budget_allocation numeric(15,2),
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: departments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.departments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: departments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.departments_id_seq OWNED BY public.departments.id;


--
-- Name: engineering_projects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.engineering_projects (
    id bigint NOT NULL,
    project_number character varying(255) NOT NULL,
    project_name character varying(255) NOT NULL,
    project_type character varying(255) NOT NULL,
    description text NOT NULL,
    location text NOT NULL,
    estimated_cost numeric(15,2) NOT NULL,
    actual_cost numeric(15,2),
    start_date date NOT NULL,
    planned_completion_date date NOT NULL,
    actual_completion_date date,
    project_manager character varying(255) NOT NULL,
    contractor character varying(255),
    status character varying(255) DEFAULT 'planning'::character varying NOT NULL,
    completion_percentage integer DEFAULT 0 NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: engineering_projects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.engineering_projects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: engineering_projects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.engineering_projects_id_seq OWNED BY public.engineering_projects.id;


--
-- Name: event_permits; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.event_permits (
    id bigint NOT NULL,
    permit_number character varying(255) NOT NULL,
    event_name character varying(255) NOT NULL,
    organizer_name character varying(255) NOT NULL,
    organizer_contact character varying(255) NOT NULL,
    event_type character varying(255) NOT NULL,
    event_description text NOT NULL,
    venue_location text NOT NULL,
    event_date date NOT NULL,
    start_time time(0) without time zone NOT NULL,
    end_time time(0) without time zone NOT NULL,
    expected_attendance integer NOT NULL,
    permit_fee numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    conditions text,
    rejection_reason text,
    application_date date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: event_permits_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.event_permits_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: event_permits_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.event_permits_id_seq OWNED BY public.event_permits.id;


--
-- Name: exchange_rate_histories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.exchange_rate_histories (
    id bigint NOT NULL,
    currency_code character varying(3) NOT NULL,
    exchange_rate numeric(10,6) NOT NULL,
    effective_date date NOT NULL,
    created_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: exchange_rate_histories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.exchange_rate_histories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: exchange_rate_histories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.exchange_rate_histories_id_seq OWNED BY public.exchange_rate_histories.id;


--
-- Name: facilities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.facilities (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    location character varying(255) NOT NULL,
    capacity integer NOT NULL,
    description text,
    amenities json,
    hourly_rate numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    daily_rate numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'available'::character varying NOT NULL,
    operating_hours json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: facilities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.facilities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: facilities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.facilities_id_seq OWNED BY public.facilities.id;


--
-- Name: facility_bookings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.facility_bookings (
    id bigint NOT NULL,
    booking_number character varying(255) NOT NULL,
    facility_id bigint NOT NULL,
    customer_id bigint NOT NULL,
    event_name character varying(255) NOT NULL,
    event_description text,
    booking_date date NOT NULL,
    start_time time(0) without time zone NOT NULL,
    end_time time(0) without time zone NOT NULL,
    expected_attendees integer NOT NULL,
    total_cost numeric(10,2) NOT NULL,
    deposit_paid numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    special_requirements text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: facility_bookings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.facility_bookings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: facility_bookings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.facility_bookings_id_seq OWNED BY public.facility_bookings.id;


--
-- Name: fdms_receipts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fdms_receipts (
    id bigint NOT NULL,
    receipt_number character varying(255) NOT NULL,
    fiscal_receipt_number character varying(255),
    customer_id bigint,
    cashier_id bigint NOT NULL,
    receipt_date date NOT NULL,
    receipt_time timestamp(0) without time zone NOT NULL,
    payment_method character varying(255) NOT NULL,
    currency_code character varying(3) NOT NULL,
    exchange_rate numeric(10,6) DEFAULT '1'::numeric NOT NULL,
    subtotal_amount numeric(15,2) NOT NULL,
    tax_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    total_amount numeric(15,2) NOT NULL,
    amount_tendered numeric(15,2) NOT NULL,
    change_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    fiscal_device_id character varying(255),
    fiscal_signature text,
    qr_code text,
    verification_code character varying(255),
    fdms_transmitted boolean DEFAULT false NOT NULL,
    fdms_transmission_date timestamp(0) without time zone,
    fdms_response json,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    voided_at timestamp(0) without time zone,
    void_reason text,
    original_receipt_id bigint,
    items json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT fdms_receipts_payment_method_check CHECK (((payment_method)::text = ANY (ARRAY[('cash'::character varying)::text, ('card'::character varying)::text, ('mobile'::character varying)::text, ('bank_transfer'::character varying)::text, ('cheque'::character varying)::text]))),
    CONSTRAINT fdms_receipts_status_check CHECK (((status)::text = ANY (ARRAY[('active'::character varying)::text, ('voided'::character varying)::text, ('cancelled'::character varying)::text])))
);


--
-- Name: fdms_receipts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fdms_receipts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fdms_receipts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fdms_receipts_id_seq OWNED BY public.fdms_receipts.id;


--
-- Name: fdms_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fdms_settings (
    id bigint NOT NULL,
    operator_id character varying(255) NOT NULL,
    terminal_id character varying(255) NOT NULL,
    certificate_path character varying(255) NOT NULL,
    api_endpoint character varying(255) NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: fdms_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fdms_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fdms_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fdms_settings_id_seq OWNED BY public.fdms_settings.id;


--
-- Name: finance_budgets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_budgets (
    id bigint NOT NULL,
    budget_name character varying(255) NOT NULL,
    financial_year character varying(255) NOT NULL,
    account_id bigint NOT NULL,
    budgeted_amount numeric(15,2) NOT NULL,
    actual_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    variance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    period character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_budgets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_budgets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_budgets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_budgets_id_seq OWNED BY public.finance_budgets.id;


--
-- Name: finance_chart_of_accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_chart_of_accounts (
    id bigint NOT NULL,
    account_code character varying(255) NOT NULL,
    account_name character varying(255) NOT NULL,
    account_type character varying(255) NOT NULL,
    account_category character varying(255) NOT NULL,
    parent_account_id integer,
    description text,
    is_active boolean DEFAULT true NOT NULL,
    opening_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    current_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: finance_chart_of_accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_chart_of_accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_chart_of_accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_chart_of_accounts_id_seq OWNED BY public.finance_chart_of_accounts.id;


--
-- Name: finance_general_journal_headers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_general_journal_headers (
    id bigint NOT NULL,
    journal_number character varying(255) NOT NULL,
    journal_date date NOT NULL,
    reference character varying(255),
    description text NOT NULL,
    journal_type character varying(255) DEFAULT 'general'::character varying NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    total_debits numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    total_credits numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    created_by bigint NOT NULL,
    approved_by bigint,
    posted_by bigint,
    approved_at timestamp(0) without time zone,
    posted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT finance_general_journal_headers_journal_type_check CHECK (((journal_type)::text = ANY ((ARRAY['general'::character varying, 'recurring'::character varying, 'reversing'::character varying, 'closing'::character varying])::text[]))),
    CONSTRAINT finance_general_journal_headers_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'approved'::character varying, 'posted'::character varying])::text[])))
);


--
-- Name: finance_general_journal_headers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_general_journal_headers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_general_journal_headers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_general_journal_headers_id_seq OWNED BY public.finance_general_journal_headers.id;


--
-- Name: finance_general_journal_lines; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_general_journal_lines (
    id bigint NOT NULL,
    journal_header_id bigint NOT NULL,
    account_code character varying(255) NOT NULL,
    description text NOT NULL,
    debit_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    credit_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    line_number integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_general_journal_lines_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_general_journal_lines_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_general_journal_lines_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_general_journal_lines_id_seq OWNED BY public.finance_general_journal_lines.id;


--
-- Name: finance_general_ledger; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_general_ledger (
    id bigint NOT NULL,
    transaction_number character varying(255) NOT NULL,
    account_id bigint NOT NULL,
    transaction_date date NOT NULL,
    transaction_type character varying(255) NOT NULL,
    amount numeric(15,2) NOT NULL,
    description text NOT NULL,
    reference_number character varying(255),
    source_document character varying(255),
    created_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_general_ledger_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_general_ledger_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_general_ledger_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_general_ledger_id_seq OWNED BY public.finance_general_ledger.id;


--
-- Name: finance_invoice_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_invoice_items (
    id bigint NOT NULL,
    invoice_id bigint NOT NULL,
    description character varying(255) NOT NULL,
    quantity integer NOT NULL,
    unit_price numeric(10,2) NOT NULL,
    line_total numeric(15,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_invoice_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_invoice_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_invoice_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_invoice_items_id_seq OWNED BY public.finance_invoice_items.id;


--
-- Name: finance_invoices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_invoices (
    id bigint NOT NULL,
    invoice_number character varying(255) NOT NULL,
    customer_id bigint NOT NULL,
    invoice_date date NOT NULL,
    due_date date NOT NULL,
    subtotal numeric(15,2) NOT NULL,
    vat_amount numeric(15,2) NOT NULL,
    total_amount numeric(15,2) NOT NULL,
    amount_paid numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    balance numeric(15,2) NOT NULL,
    status character varying(255) DEFAULT 'unpaid'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_invoices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_invoices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_invoices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_invoices_id_seq OWNED BY public.finance_invoices.id;


--
-- Name: finance_payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_payments (
    id bigint NOT NULL,
    payment_number character varying(255) NOT NULL,
    invoice_id bigint,
    customer_id bigint NOT NULL,
    payment_date date NOT NULL,
    amount numeric(15,2) NOT NULL,
    payment_method character varying(255) NOT NULL,
    reference_number character varying(255),
    notes text,
    processed_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_payments_id_seq OWNED BY public.finance_payments.id;


--
-- Name: financial_reports; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.financial_reports (
    id bigint NOT NULL,
    report_type character varying(255) NOT NULL,
    report_name character varying(255) NOT NULL,
    report_date date NOT NULL,
    report_data json,
    status character varying(255) DEFAULT 'generating'::character varying NOT NULL,
    created_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT financial_reports_status_check CHECK (((status)::text = ANY (ARRAY[('generating'::character varying)::text, ('generated'::character varying)::text, ('failed'::character varying)::text])))
);


--
-- Name: financial_reports_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.financial_reports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: financial_reports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.financial_reports_id_seq OWNED BY public.financial_reports.id;


--
-- Name: fiscal_devices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fiscal_devices (
    id bigint NOT NULL,
    device_id character varying(255) NOT NULL,
    device_name character varying(255) NOT NULL,
    device_type character varying(255) NOT NULL,
    serial_number character varying(255) NOT NULL,
    manufacturer character varying(255) NOT NULL,
    firmware_version character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    last_sync timestamp(0) without time zone,
    configuration json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: fiscal_devices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fiscal_devices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fiscal_devices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fiscal_devices_id_seq OWNED BY public.fiscal_devices.id;


--
-- Name: fixed_assets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fixed_assets (
    id bigint NOT NULL,
    asset_number character varying(255) NOT NULL,
    asset_name character varying(255) NOT NULL,
    asset_description text,
    asset_category character varying(255) NOT NULL,
    asset_location character varying(255),
    acquisition_date date NOT NULL,
    acquisition_cost numeric(15,2) NOT NULL,
    currency_code character varying(3) NOT NULL,
    depreciation_rate numeric(5,2) NOT NULL,
    depreciation_method character varying(255) NOT NULL,
    useful_life_years integer NOT NULL,
    residual_value numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    accumulated_depreciation numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    current_value numeric(15,2) NOT NULL,
    custodian character varying(255),
    condition character varying(255) DEFAULT 'good'::character varying NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    disposal_date date,
    disposal_amount numeric(15,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT fixed_assets_condition_check CHECK (((condition)::text = ANY (ARRAY[('excellent'::character varying)::text, ('good'::character varying)::text, ('fair'::character varying)::text, ('poor'::character varying)::text]))),
    CONSTRAINT fixed_assets_depreciation_method_check CHECK (((depreciation_method)::text = ANY (ARRAY[('straight_line'::character varying)::text, ('reducing_balance'::character varying)::text, ('units_production'::character varying)::text]))),
    CONSTRAINT fixed_assets_status_check CHECK (((status)::text = ANY (ARRAY[('active'::character varying)::text, ('disposed'::character varying)::text, ('written_off'::character varying)::text])))
);


--
-- Name: fixed_assets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fixed_assets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fixed_assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fixed_assets_id_seq OWNED BY public.fixed_assets.id;


--
-- Name: fleet_vehicles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fleet_vehicles (
    id bigint NOT NULL,
    vehicle_number character varying(255) NOT NULL,
    registration_number character varying(255) NOT NULL,
    make character varying(255) NOT NULL,
    model character varying(255) NOT NULL,
    year integer NOT NULL,
    vehicle_type character varying(255) NOT NULL,
    department_id bigint NOT NULL,
    assigned_driver character varying(255),
    purchase_cost numeric(15,2),
    purchase_date date,
    current_odometer integer DEFAULT 0 NOT NULL,
    license_expiry_date date NOT NULL,
    insurance_expiry_date date NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: fleet_vehicles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fleet_vehicles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fleet_vehicles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fleet_vehicles_id_seq OWNED BY public.fleet_vehicles.id;


--
-- Name: gate_takings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gate_takings (
    id bigint NOT NULL,
    facility_id bigint NOT NULL,
    date date NOT NULL,
    time_period_start time(0) without time zone NOT NULL,
    time_period_end time(0) without time zone NOT NULL,
    adult_tickets integer NOT NULL,
    adult_price numeric(8,2) NOT NULL,
    child_tickets integer NOT NULL,
    child_price numeric(8,2) NOT NULL,
    senior_tickets integer NOT NULL,
    senior_price numeric(8,2) NOT NULL,
    total_amount numeric(10,2) NOT NULL,
    collected_by character varying(255) NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gate_takings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gate_takings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gate_takings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gate_takings_id_seq OWNED BY public.gate_takings.id;


--
-- Name: general_ledger; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.general_ledger (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: general_ledger_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.general_ledger_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: general_ledger_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.general_ledger_id_seq OWNED BY public.general_ledger.id;


--
-- Name: health_facilities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.health_facilities (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    address text NOT NULL,
    contact_person character varying(255) NOT NULL,
    phone character varying(255) NOT NULL,
    email character varying(255),
    license_number character varying(255) NOT NULL,
    license_expiry_date date NOT NULL,
    services_offered json,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: health_facilities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.health_facilities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: health_facilities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.health_facilities_id_seq OWNED BY public.health_facilities.id;


--
-- Name: health_inspections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.health_inspections (
    id bigint NOT NULL,
    inspection_number character varying(255) NOT NULL,
    facility_id bigint,
    inspection_type character varying(255) NOT NULL,
    inspection_date date NOT NULL,
    inspector_name character varying(255) NOT NULL,
    findings text NOT NULL,
    compliance_status character varying(255) NOT NULL,
    recommendations text,
    follow_up_date date,
    status character varying(255) DEFAULT 'completed'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: health_inspections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.health_inspections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: health_inspections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.health_inspections_id_seq OWNED BY public.health_inspections.id;


--
-- Name: housing_allocations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.housing_allocations (
    id bigint NOT NULL,
    application_id bigint NOT NULL,
    property_id bigint NOT NULL,
    allocation_date date NOT NULL,
    lease_start_date date NOT NULL,
    lease_end_date date NOT NULL,
    monthly_rent numeric(10,2) NOT NULL,
    deposit_amount numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    terms_conditions text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: housing_allocations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.housing_allocations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: housing_allocations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.housing_allocations_id_seq OWNED BY public.housing_allocations.id;


--
-- Name: housing_applications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.housing_applications (
    id bigint NOT NULL,
    application_number character varying(255) NOT NULL,
    applicant_name character varying(255) NOT NULL,
    id_number character varying(255) NOT NULL,
    email character varying(255),
    phone character varying(255) NOT NULL,
    current_address text NOT NULL,
    household_size integer NOT NULL,
    monthly_income numeric(10,2) NOT NULL,
    employment_status character varying(255) NOT NULL,
    preferred_area character varying(255),
    property_type_preference character varying(255),
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    applied_at timestamp(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: housing_applications_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.housing_applications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: housing_applications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.housing_applications_id_seq OWNED BY public.housing_applications.id;


--
-- Name: housing_properties; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.housing_properties (
    id bigint NOT NULL,
    property_number character varying(255) NOT NULL,
    property_type character varying(255) NOT NULL,
    address character varying(255) NOT NULL,
    suburb character varying(255) NOT NULL,
    city character varying(255) NOT NULL,
    postal_code character varying(255) NOT NULL,
    bedrooms integer NOT NULL,
    bathrooms integer NOT NULL,
    size_sqm numeric(8,2),
    rental_amount numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'available'::character varying NOT NULL,
    description text,
    amenities json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: housing_properties_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.housing_properties_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: housing_properties_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.housing_properties_id_seq OWNED BY public.housing_properties.id;


--
-- Name: housing_waiting_list; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.housing_waiting_list (
    id bigint NOT NULL,
    application_id bigint NOT NULL,
    "position" integer NOT NULL,
    category character varying(255) NOT NULL,
    priority_score integer NOT NULL,
    date_added date NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: housing_waiting_list_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.housing_waiting_list_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: housing_waiting_list_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.housing_waiting_list_id_seq OWNED BY public.housing_waiting_list.id;


--
-- Name: hr_attendance; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hr_attendance (
    id bigint NOT NULL,
    employee_id bigint NOT NULL,
    date date NOT NULL,
    time_in time(0) without time zone,
    time_out time(0) without time zone,
    hours_worked numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    overtime_hours numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: hr_attendance_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hr_attendance_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hr_attendance_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hr_attendance_id_seq OWNED BY public.hr_attendance.id;


--
-- Name: hr_employees; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hr_employees (
    id bigint NOT NULL,
    employee_number character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    id_number character varying(255) NOT NULL,
    email character varying(255),
    phone character varying(255) NOT NULL,
    address text NOT NULL,
    date_of_birth date NOT NULL,
    gender character varying(255) NOT NULL,
    department_id bigint NOT NULL,
    "position" character varying(255) NOT NULL,
    employment_type character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date,
    basic_salary numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: hr_employees_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hr_employees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hr_employees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hr_employees_id_seq OWNED BY public.hr_employees.id;


--
-- Name: hr_payroll; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hr_payroll (
    id bigint NOT NULL,
    employee_id bigint NOT NULL,
    pay_period character varying(255) NOT NULL,
    pay_date date NOT NULL,
    basic_salary numeric(10,2) NOT NULL,
    overtime_amount numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    allowances numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    gross_salary numeric(10,2) NOT NULL,
    tax_deduction numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    uif_deduction numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    other_deductions numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    net_salary numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: hr_payroll_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hr_payroll_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hr_payroll_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hr_payroll_id_seq OWNED BY public.hr_payroll.id;


--
-- Name: infrastructure_assets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.infrastructure_assets (
    id bigint NOT NULL,
    asset_number character varying(255) NOT NULL,
    asset_name character varying(255) NOT NULL,
    asset_type character varying(255) NOT NULL,
    category character varying(255) NOT NULL,
    location text NOT NULL,
    description text,
    installation_date date,
    original_cost numeric(15,2),
    current_value numeric(15,2),
    condition character varying(255) NOT NULL,
    last_inspection_date date,
    next_inspection_date date,
    expected_life_years integer,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: infrastructure_assets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.infrastructure_assets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: infrastructure_assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.infrastructure_assets_id_seq OWNED BY public.infrastructure_assets.id;


--
-- Name: inventory_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_categories (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    parent_category_id integer,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: inventory_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.inventory_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: inventory_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.inventory_categories_id_seq OWNED BY public.inventory_categories.id;


--
-- Name: inventory_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_items (
    id bigint NOT NULL,
    item_code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    category_id bigint NOT NULL,
    unit_of_measure character varying(255) NOT NULL,
    unit_cost numeric(10,2) NOT NULL,
    current_stock integer DEFAULT 0 NOT NULL,
    minimum_stock_level integer DEFAULT 0 NOT NULL,
    maximum_stock_level integer DEFAULT 0 NOT NULL,
    location character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: inventory_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.inventory_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: inventory_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.inventory_items_id_seq OWNED BY public.inventory_items.id;


--
-- Name: inventory_transactions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_transactions (
    id bigint NOT NULL,
    transaction_number character varying(255) NOT NULL,
    item_id bigint NOT NULL,
    transaction_type character varying(255) NOT NULL,
    quantity integer NOT NULL,
    unit_cost numeric(10,2) NOT NULL,
    total_cost numeric(15,2) NOT NULL,
    transaction_date date NOT NULL,
    reference_document character varying(255),
    notes text,
    processed_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: inventory_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.inventory_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: inventory_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.inventory_transactions_id_seq OWNED BY public.inventory_transactions.id;


--
-- Name: licensing_business_licenses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.licensing_business_licenses (
    id bigint NOT NULL,
    license_number character varying(255) NOT NULL,
    business_name character varying(255) NOT NULL,
    business_type character varying(255) NOT NULL,
    owner_name character varying(255) NOT NULL,
    owner_id_number character varying(255) NOT NULL,
    business_address text NOT NULL,
    contact_phone character varying(255) NOT NULL,
    contact_email character varying(255),
    issue_date date NOT NULL,
    expiry_date date NOT NULL,
    license_fee numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    conditions text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: licensing_business_licenses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.licensing_business_licenses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: licensing_business_licenses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.licensing_business_licenses_id_seq OWNED BY public.licensing_business_licenses.id;


--
-- Name: market_stalls; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.market_stalls (
    id bigint NOT NULL,
    market_id bigint NOT NULL,
    stall_number character varying(255) NOT NULL,
    section character varying(255) NOT NULL,
    size_sqm numeric(8,2) NOT NULL,
    daily_rate numeric(8,2) NOT NULL,
    monthly_rate numeric(8,2) NOT NULL,
    stall_type character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'available'::character varying NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: market_stalls_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.market_stalls_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: market_stalls_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.market_stalls_id_seq OWNED BY public.market_stalls.id;


--
-- Name: markets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.markets (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    address text NOT NULL,
    market_type character varying(255) NOT NULL,
    total_stalls integer NOT NULL,
    occupied_stalls integer DEFAULT 0 NOT NULL,
    operating_days json NOT NULL,
    opening_time time(0) without time zone NOT NULL,
    closing_time time(0) without time zone NOT NULL,
    market_manager character varying(255) NOT NULL,
    contact_phone character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: markets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.markets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: markets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.markets_id_seq OWNED BY public.markets.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: municipal_bills; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.municipal_bills (
    id bigint NOT NULL,
    bill_number character varying(255) NOT NULL,
    customer_account_id bigint NOT NULL,
    council_id bigint NOT NULL,
    bill_date date NOT NULL,
    due_date date NOT NULL,
    billing_period character varying(255) NOT NULL,
    subtotal numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    tax_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    discount_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    penalty_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    total_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    paid_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    outstanding_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    sent_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT municipal_bills_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'pending'::character varying, 'sent'::character varying, 'paid'::character varying, 'overdue'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: municipal_bills_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.municipal_bills_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: municipal_bills_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.municipal_bills_id_seq OWNED BY public.municipal_bills.id;


--
-- Name: municipal_service_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.municipal_service_categories (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: municipal_service_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.municipal_service_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: municipal_service_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.municipal_service_categories_id_seq OWNED BY public.municipal_service_categories.id;


--
-- Name: municipal_services; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.municipal_services (
    id bigint NOT NULL,
    service_type_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    fee numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    processing_days integer DEFAULT 1 NOT NULL,
    required_documents json,
    department character varying(255),
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: municipal_services_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.municipal_services_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: municipal_services_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.municipal_services_id_seq OWNED BY public.municipal_services.id;


--
-- Name: offices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.offices (
    id bigint NOT NULL,
    council_id bigint NOT NULL,
    department_id bigint,
    name character varying(255) NOT NULL,
    location character varying(255),
    address text,
    phone character varying(255),
    email character varying(255),
    office_type character varying(255),
    capacity integer,
    facilities json,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: offices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.offices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: offices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.offices_id_seq OWNED BY public.offices.id;


--
-- Name: parking_violations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.parking_violations (
    id bigint NOT NULL,
    violation_number character varying(255) NOT NULL,
    vehicle_registration character varying(255) NOT NULL,
    zone_id bigint NOT NULL,
    violation_type character varying(255) NOT NULL,
    violation_datetime timestamp(0) without time zone NOT NULL,
    officer_name character varying(255) NOT NULL,
    fine_amount numeric(8,2) NOT NULL,
    status character varying(255) DEFAULT 'unpaid'::character varying NOT NULL,
    due_date date NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: parking_violations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.parking_violations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: parking_violations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.parking_violations_id_seq OWNED BY public.parking_violations.id;


--
-- Name: parking_zones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.parking_zones (
    id bigint NOT NULL,
    zone_name character varying(255) NOT NULL,
    zone_code character varying(255) NOT NULL,
    description text NOT NULL,
    hourly_rate numeric(8,2) NOT NULL,
    daily_rate numeric(8,2) NOT NULL,
    max_parking_hours integer NOT NULL,
    operating_hours json NOT NULL,
    restricted_days json,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: parking_zones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.parking_zones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: parking_zones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.parking_zones_id_seq OWNED BY public.parking_zones.id;


--
-- Name: payment_methods; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.payment_methods (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    is_active boolean DEFAULT true NOT NULL,
    transaction_fee_percentage numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    transaction_fee_fixed numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    configuration json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: payment_methods_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.payment_methods_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payment_methods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.payment_methods_id_seq OWNED BY public.payment_methods.id;


--
-- Name: payment_vouchers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.payment_vouchers (
    id bigint NOT NULL,
    voucher_number character varying(255) NOT NULL,
    bank_account_id bigint,
    payee_name character varying(255) NOT NULL,
    payee_address text,
    amount numeric(15,2) NOT NULL,
    currency character varying(3) DEFAULT 'USD'::character varying NOT NULL,
    payment_date date NOT NULL,
    payment_method character varying(255) NOT NULL,
    purpose character varying(255) NOT NULL,
    description text,
    reference_number character varying(255),
    priority character varying(255) DEFAULT 'normal'::character varying NOT NULL,
    invoice_number character varying(255),
    due_date date,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    requested_by bigint,
    approved_by bigint,
    approved_at timestamp(0) without time zone,
    approval_notes text,
    vendor_id bigint,
    bill_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT payment_vouchers_priority_check CHECK (((priority)::text = ANY ((ARRAY['low'::character varying, 'normal'::character varying, 'high'::character varying, 'urgent'::character varying])::text[]))),
    CONSTRAINT payment_vouchers_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'pending_approval'::character varying, 'approved'::character varying, 'paid'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: payment_vouchers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.payment_vouchers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payment_vouchers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.payment_vouchers_id_seq OWNED BY public.payment_vouchers.id;


--
-- Name: planning_applications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.planning_applications (
    id bigint NOT NULL,
    application_number character varying(255) NOT NULL,
    property_id bigint NOT NULL,
    applicant_name character varying(255) NOT NULL,
    applicant_contact character varying(255) NOT NULL,
    application_type character varying(255) NOT NULL,
    description text NOT NULL,
    proposed_development text NOT NULL,
    estimated_cost numeric(15,2),
    application_date date NOT NULL,
    target_decision_date date,
    status character varying(255) DEFAULT 'submitted'::character varying NOT NULL,
    conditions text,
    rejection_reason text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: planning_applications_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.planning_applications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: planning_applications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.planning_applications_id_seq OWNED BY public.planning_applications.id;


--
-- Name: pos_terminals; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pos_terminals (
    id bigint NOT NULL,
    terminal_id character varying(255) NOT NULL,
    terminal_name character varying(255) NOT NULL,
    location character varying(255) NOT NULL,
    serial_number character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    configuration json,
    created_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: pos_terminals_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.pos_terminals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pos_terminals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.pos_terminals_id_seq OWNED BY public.pos_terminals.id;


--
-- Name: program_budgets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.program_budgets (
    id bigint NOT NULL,
    program_code character varying(255) NOT NULL,
    program_name character varying(255) NOT NULL,
    program_description text,
    budget_year integer NOT NULL,
    allocated_amount numeric(15,2) NOT NULL,
    committed_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    actual_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) NOT NULL,
    responsible_officer bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT program_budgets_status_check CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('approved'::character varying)::text, ('active'::character varying)::text, ('closed'::character varying)::text])))
);


--
-- Name: program_budgets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.program_budgets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: program_budgets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.program_budgets_id_seq OWNED BY public.program_budgets.id;


--
-- Name: properties; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.properties (
    id bigint NOT NULL,
    property_number character varying(255) NOT NULL,
    erf_number character varying(255),
    title_deed_number character varying(255),
    property_type character varying(255) NOT NULL,
    address text NOT NULL,
    suburb character varying(255) NOT NULL,
    city character varying(255) NOT NULL,
    postal_code character varying(255) NOT NULL,
    size_hectares numeric(10,4),
    market_value numeric(15,2),
    municipal_value numeric(15,2),
    zoning character varying(255),
    ownership_type character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: properties_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.properties_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: properties_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.properties_id_seq OWNED BY public.properties.id;


--
-- Name: property_leases; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.property_leases (
    id bigint NOT NULL,
    lease_number character varying(255) NOT NULL,
    property_id bigint NOT NULL,
    lessee_name character varying(255) NOT NULL,
    lessee_id_number character varying(255) NOT NULL,
    lessee_contact character varying(255) NOT NULL,
    lease_start_date date NOT NULL,
    lease_end_date date NOT NULL,
    monthly_rental numeric(10,2) NOT NULL,
    annual_escalation_percentage numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    deposit_amount numeric(10,2) NOT NULL,
    lease_purpose character varying(255) NOT NULL,
    special_conditions text,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: property_leases_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.property_leases_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: property_leases_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.property_leases_id_seq OWNED BY public.property_leases.id;


--
-- Name: property_rates; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.property_rates (
    id bigint NOT NULL,
    property_id bigint NOT NULL,
    financial_year character varying(255) NOT NULL,
    municipal_value numeric(15,2) NOT NULL,
    rate_cent_amount numeric(8,4) NOT NULL,
    annual_rates numeric(15,2) NOT NULL,
    refuse_charges numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    sewerage_charges numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    other_charges numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    total_amount numeric(15,2) NOT NULL,
    amount_paid numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    outstanding_balance numeric(15,2) NOT NULL,
    status character varying(255) DEFAULT 'current'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: property_rates_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.property_rates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: property_rates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.property_rates_id_seq OWNED BY public.property_rates.id;


--
-- Name: property_valuations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.property_valuations (
    id bigint NOT NULL,
    property_id bigint NOT NULL,
    valuation_date date NOT NULL,
    land_value numeric(15,2) NOT NULL,
    improvement_value numeric(15,2) NOT NULL,
    total_value numeric(15,2) NOT NULL,
    valuation_method character varying(255) NOT NULL,
    valuer_name character varying(255) NOT NULL,
    valuer_registration_number character varying(255),
    notes text,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: property_valuations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.property_valuations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: property_valuations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.property_valuations_id_seq OWNED BY public.property_valuations.id;


--
-- Name: purchase_order_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.purchase_order_items (
    id bigint NOT NULL,
    purchase_order_id bigint NOT NULL,
    item_id bigint NOT NULL,
    quantity numeric(10,2) NOT NULL,
    unit_price numeric(10,2) NOT NULL,
    total_price numeric(15,2) NOT NULL,
    quantity_received numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    received_date date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: purchase_order_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.purchase_order_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: purchase_order_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.purchase_order_items_id_seq OWNED BY public.purchase_order_items.id;


--
-- Name: purchase_orders; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.purchase_orders (
    id bigint NOT NULL,
    po_number character varying(255) NOT NULL,
    supplier_id bigint NOT NULL,
    po_date date NOT NULL,
    delivery_date date,
    description text,
    subtotal numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    tax_rate numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    tax_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    total_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    created_by bigint NOT NULL,
    approved_by bigint,
    approved_date timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT purchase_orders_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'partially_received'::character varying, 'completed'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: purchase_orders_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.purchase_orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: purchase_orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.purchase_orders_id_seq OWNED BY public.purchase_orders.id;


--
-- Name: revenue_collections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.revenue_collections (
    id bigint NOT NULL,
    receipt_number character varying(255) NOT NULL,
    revenue_source character varying(255) NOT NULL,
    source_reference character varying(255),
    customer_id bigint NOT NULL,
    collection_date date NOT NULL,
    amount_collected numeric(15,2) NOT NULL,
    payment_method character varying(255) NOT NULL,
    payment_reference character varying(255),
    collected_by bigint NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: revenue_collections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.revenue_collections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: revenue_collections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.revenue_collections_id_seq OWNED BY public.revenue_collections.id;


--
-- Name: service_request_attachments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.service_request_attachments (
    id bigint NOT NULL,
    service_request_id bigint NOT NULL,
    filename character varying(255) NOT NULL,
    original_name character varying(255) NOT NULL,
    mime_type character varying(255) NOT NULL,
    file_size bigint NOT NULL,
    file_path character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: service_request_attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.service_request_attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: service_request_attachments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.service_request_attachments_id_seq OWNED BY public.service_request_attachments.id;


--
-- Name: service_requests; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.service_requests (
    id bigint NOT NULL,
    request_number character varying(255) NOT NULL,
    customer_id bigint NOT NULL,
    service_type_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    priority character varying(255) DEFAULT 'medium'::character varying NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    assigned_to bigint,
    expected_completion_date date,
    completed_at timestamp(0) without time zone,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: service_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.service_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: service_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.service_requests_id_seq OWNED BY public.service_requests.id;


--
-- Name: service_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.service_types (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    fee numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    processing_days integer DEFAULT 1 NOT NULL,
    required_documents json,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: service_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.service_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: service_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.service_types_id_seq OWNED BY public.service_types.id;


--
-- Name: stall_allocations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.stall_allocations (
    id bigint NOT NULL,
    stall_id bigint NOT NULL,
    trader_name character varying(255) NOT NULL,
    trader_id_number character varying(255) NOT NULL,
    trader_contact character varying(255) NOT NULL,
    business_type character varying(255) NOT NULL,
    allocation_date date NOT NULL,
    allocation_type character varying(255) NOT NULL,
    rental_amount numeric(10,2) NOT NULL,
    deposit_paid numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: stall_allocations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.stall_allocations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: stall_allocations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.stall_allocations_id_seq OWNED BY public.stall_allocations.id;


--
-- Name: suppliers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.suppliers (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    contact_person character varying(255),
    email character varying(255),
    phone character varying(255),
    address text,
    city character varying(255),
    state character varying(255),
    postal_code character varying(255),
    country character varying(255) DEFAULT 'Zimbabwe'::character varying NOT NULL,
    tax_number character varying(255),
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT suppliers_status_check CHECK (((status)::text = ANY ((ARRAY['active'::character varying, 'inactive'::character varying])::text[])))
);


--
-- Name: suppliers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.suppliers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: suppliers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.suppliers_id_seq OWNED BY public.suppliers.id;


--
-- Name: survey_projects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.survey_projects (
    id bigint NOT NULL,
    project_number character varying(255) NOT NULL,
    project_name character varying(255) NOT NULL,
    client_name character varying(255) NOT NULL,
    client_contact character varying(255) NOT NULL,
    survey_type character varying(255) NOT NULL,
    project_description text NOT NULL,
    location text NOT NULL,
    start_date date NOT NULL,
    expected_completion_date date NOT NULL,
    actual_completion_date date,
    surveyor_name character varying(255) NOT NULL,
    surveyor_registration character varying(255) NOT NULL,
    project_fee numeric(15,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: survey_projects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.survey_projects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: survey_projects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.survey_projects_id_seq OWNED BY public.survey_projects.id;


--
-- Name: tenants; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tenants (
    id bigint NOT NULL,
    allocation_id bigint NOT NULL,
    tenant_number character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    id_number character varying(255) NOT NULL,
    email character varying(255),
    phone character varying(255) NOT NULL,
    emergency_contact_name character varying(255) NOT NULL,
    emergency_contact_phone character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: tenants_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tenants_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tenants_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tenants_id_seq OWNED BY public.tenants.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    council_id bigint NOT NULL,
    department_id bigint,
    office_id bigint,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    employee_id character varying(255),
    phone character varying(255),
    "position" character varying(255),
    role character varying(255) DEFAULT 'employee'::character varying NOT NULL,
    permissions json,
    hire_date date,
    salary numeric(10,2),
    employment_status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    profile_photo character varying(255),
    active boolean DEFAULT true NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT users_employment_status_check CHECK (((employment_status)::text = ANY (ARRAY[('active'::character varying)::text, ('inactive'::character varying)::text, ('suspended'::character varying)::text, ('terminated'::character varying)::text]))),
    CONSTRAINT users_role_check CHECK (((role)::text = ANY (ARRAY[('admin'::character varying)::text, ('manager'::character varying)::text, ('employee'::character varying)::text, ('super_admin'::character varying)::text])))
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: utilities_connections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.utilities_connections (
    id bigint NOT NULL,
    connection_number character varying(255) NOT NULL,
    property_id bigint NOT NULL,
    customer_id bigint NOT NULL,
    utility_type character varying(255) NOT NULL,
    meter_number character varying(255),
    connection_type character varying(255) NOT NULL,
    connection_date date NOT NULL,
    connection_fee numeric(10,2) NOT NULL,
    deposit_amount numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: utilities_connections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.utilities_connections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: utilities_connections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.utilities_connections_id_seq OWNED BY public.utilities_connections.id;


--
-- Name: voucher_lines; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.voucher_lines (
    id bigint NOT NULL,
    voucher_id bigint NOT NULL,
    account_code character varying(255) NOT NULL,
    description text NOT NULL,
    debit_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    credit_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: voucher_lines_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.voucher_lines_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: voucher_lines_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.voucher_lines_id_seq OWNED BY public.voucher_lines.id;


--
-- Name: vouchers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.vouchers (
    id bigint NOT NULL,
    voucher_number character varying(255) NOT NULL,
    voucher_type character varying(255) NOT NULL,
    voucher_date date NOT NULL,
    description text NOT NULL,
    total_amount numeric(15,2) NOT NULL,
    currency_code character varying(3) NOT NULL,
    payee_name character varying(255),
    payment_method character varying(255),
    reference_number character varying(255),
    prepared_by bigint NOT NULL,
    approved_by bigint,
    approved_at timestamp(0) without time zone,
    status character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT vouchers_payment_method_check CHECK (((payment_method)::text = ANY (ARRAY[('cash'::character varying)::text, ('cheque'::character varying)::text, ('electronic'::character varying)::text, ('mobile'::character varying)::text]))),
    CONSTRAINT vouchers_status_check CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('pending_approval'::character varying)::text, ('approved'::character varying)::text, ('paid'::character varying)::text, ('cancelled'::character varying)::text]))),
    CONSTRAINT vouchers_voucher_type_check CHECK (((voucher_type)::text = ANY (ARRAY[('payment'::character varying)::text, ('receipt'::character varying)::text, ('journal'::character varying)::text])))
);


--
-- Name: vouchers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.vouchers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: vouchers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.vouchers_id_seq OWNED BY public.vouchers.id;


--
-- Name: waste_collection_routes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.waste_collection_routes (
    id bigint NOT NULL,
    route_name character varying(255) NOT NULL,
    route_code character varying(255) NOT NULL,
    route_description text NOT NULL,
    collection_days json NOT NULL,
    start_time time(0) without time zone NOT NULL,
    estimated_completion_time time(0) without time zone NOT NULL,
    estimated_households integer NOT NULL,
    assigned_vehicle_id bigint,
    assigned_driver character varying(255),
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: waste_collection_routes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.waste_collection_routes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: waste_collection_routes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.waste_collection_routes_id_seq OWNED BY public.waste_collection_routes.id;


--
-- Name: water_bills; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.water_bills (
    id bigint NOT NULL,
    bill_number character varying(255) NOT NULL,
    connection_id bigint NOT NULL,
    bill_date date NOT NULL,
    due_date date NOT NULL,
    billing_period character varying(255) NOT NULL,
    consumption numeric(10,2) NOT NULL,
    basic_charge numeric(10,2) NOT NULL,
    consumption_charge numeric(10,2) NOT NULL,
    vat_amount numeric(10,2) NOT NULL,
    total_amount numeric(10,2) NOT NULL,
    amount_paid numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    balance numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'unpaid'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: water_bills_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.water_bills_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: water_bills_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.water_bills_id_seq OWNED BY public.water_bills.id;


--
-- Name: water_connections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.water_connections (
    id bigint NOT NULL,
    connection_number character varying(255) NOT NULL,
    property_id bigint NOT NULL,
    customer_id bigint NOT NULL,
    meter_number character varying(255) NOT NULL,
    meter_size character varying(255) NOT NULL,
    connection_date date NOT NULL,
    connection_type character varying(255) NOT NULL,
    deposit_paid numeric(10,2) NOT NULL,
    connection_fee numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: water_connections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.water_connections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: water_connections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.water_connections_id_seq OWNED BY public.water_connections.id;


--
-- Name: water_meter_readings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.water_meter_readings (
    id bigint NOT NULL,
    connection_id bigint NOT NULL,
    reading_date date NOT NULL,
    previous_reading numeric(10,2) NOT NULL,
    current_reading numeric(10,2) NOT NULL,
    consumption numeric(10,2) NOT NULL,
    reader_name character varying(255) NOT NULL,
    notes text,
    estimated boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: water_meter_readings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.water_meter_readings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: water_meter_readings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.water_meter_readings_id_seq OWNED BY public.water_meter_readings.id;


--
-- Name: zimbabwe_chart_of_accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.zimbabwe_chart_of_accounts (
    id bigint NOT NULL,
    account_code character varying(20) NOT NULL,
    account_name character varying(255) NOT NULL,
    account_type character varying(255) NOT NULL,
    account_category character varying(50) NOT NULL,
    account_subcategory character varying(50),
    account_level integer NOT NULL,
    parent_account_code character varying(20),
    is_control_account boolean DEFAULT false NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    government_classification character varying(50),
    ipsas_classification character varying(50),
    description text,
    opening_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    current_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT zimbabwe_chart_of_accounts_account_type_check CHECK (((account_type)::text = ANY (ARRAY[('asset'::character varying)::text, ('liability'::character varying)::text, ('equity'::character varying)::text, ('revenue'::character varying)::text, ('expense'::character varying)::text])))
);


--
-- Name: zimbabwe_chart_of_accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.zimbabwe_chart_of_accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: zimbabwe_chart_of_accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.zimbabwe_chart_of_accounts_id_seq OWNED BY public.zimbabwe_chart_of_accounts.id;


--
-- Name: ap_bills id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills ALTER COLUMN id SET DEFAULT nextval('public.ap_bills_id_seq'::regclass);


--
-- Name: ap_vendors id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_vendors ALTER COLUMN id SET DEFAULT nextval('public.ap_vendors_id_seq'::regclass);


--
-- Name: ar_invoices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_invoices ALTER COLUMN id SET DEFAULT nextval('public.ar_invoices_id_seq'::regclass);


--
-- Name: ar_receipts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts ALTER COLUMN id SET DEFAULT nextval('public.ar_receipts_id_seq'::regclass);


--
-- Name: asset_categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_categories ALTER COLUMN id SET DEFAULT nextval('public.asset_categories_id_seq'::regclass);


--
-- Name: asset_depreciation_history id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_depreciation_history ALTER COLUMN id SET DEFAULT nextval('public.asset_depreciation_history_id_seq'::regclass);


--
-- Name: asset_locations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_locations ALTER COLUMN id SET DEFAULT nextval('public.asset_locations_id_seq'::regclass);


--
-- Name: bank_accounts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_accounts ALTER COLUMN id SET DEFAULT nextval('public.bank_accounts_id_seq'::regclass);


--
-- Name: bank_reconciliations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations ALTER COLUMN id SET DEFAULT nextval('public.bank_reconciliations_id_seq'::regclass);


--
-- Name: bank_statements id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_statements ALTER COLUMN id SET DEFAULT nextval('public.bank_statements_id_seq'::regclass);


--
-- Name: bill_line_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_line_items ALTER COLUMN id SET DEFAULT nextval('public.bill_line_items_id_seq'::regclass);


--
-- Name: bill_payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_payments ALTER COLUMN id SET DEFAULT nextval('public.bill_payments_id_seq'::regclass);


--
-- Name: bill_reminders id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_reminders ALTER COLUMN id SET DEFAULT nextval('public.bill_reminders_id_seq'::regclass);


--
-- Name: budgets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets ALTER COLUMN id SET DEFAULT nextval('public.budgets_id_seq'::regclass);


--
-- Name: building_inspections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.building_inspections ALTER COLUMN id SET DEFAULT nextval('public.building_inspections_id_seq'::regclass);


--
-- Name: burial_records id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.burial_records ALTER COLUMN id SET DEFAULT nextval('public.burial_records_id_seq'::regclass);


--
-- Name: cash_transactions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions ALTER COLUMN id SET DEFAULT nextval('public.cash_transactions_id_seq'::regclass);


--
-- Name: cashbook_entries id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cashbook_entries ALTER COLUMN id SET DEFAULT nextval('public.cashbook_entries_id_seq'::regclass);


--
-- Name: cemeteries id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemeteries ALTER COLUMN id SET DEFAULT nextval('public.cemeteries_id_seq'::regclass);


--
-- Name: cemetery_plots id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemetery_plots ALTER COLUMN id SET DEFAULT nextval('public.cemetery_plots_id_seq'::regclass);


--
-- Name: committee_committees id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.committee_committees ALTER COLUMN id SET DEFAULT nextval('public.committee_committees_id_seq'::regclass);


--
-- Name: committee_meetings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.committee_meetings ALTER COLUMN id SET DEFAULT nextval('public.committee_meetings_id_seq'::regclass);


--
-- Name: communications id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.communications ALTER COLUMN id SET DEFAULT nextval('public.communications_id_seq'::regclass);


--
-- Name: cost_centers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cost_centers ALTER COLUMN id SET DEFAULT nextval('public.cost_centers_id_seq'::regclass);


--
-- Name: councils id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.councils ALTER COLUMN id SET DEFAULT nextval('public.councils_id_seq'::regclass);


--
-- Name: currencies id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.currencies ALTER COLUMN id SET DEFAULT nextval('public.currencies_id_seq'::regclass);


--
-- Name: customer_accounts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_accounts ALTER COLUMN id SET DEFAULT nextval('public.customer_accounts_id_seq'::regclass);


--
-- Name: customers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers ALTER COLUMN id SET DEFAULT nextval('public.customers_id_seq'::regclass);


--
-- Name: debtor_transactions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtor_transactions ALTER COLUMN id SET DEFAULT nextval('public.debtor_transactions_id_seq'::regclass);


--
-- Name: debtors id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtors ALTER COLUMN id SET DEFAULT nextval('public.debtors_id_seq'::regclass);


--
-- Name: departments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments ALTER COLUMN id SET DEFAULT nextval('public.departments_id_seq'::regclass);


--
-- Name: engineering_projects id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.engineering_projects ALTER COLUMN id SET DEFAULT nextval('public.engineering_projects_id_seq'::regclass);


--
-- Name: event_permits id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.event_permits ALTER COLUMN id SET DEFAULT nextval('public.event_permits_id_seq'::regclass);


--
-- Name: exchange_rate_histories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.exchange_rate_histories ALTER COLUMN id SET DEFAULT nextval('public.exchange_rate_histories_id_seq'::regclass);


--
-- Name: facilities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facilities ALTER COLUMN id SET DEFAULT nextval('public.facilities_id_seq'::regclass);


--
-- Name: facility_bookings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facility_bookings ALTER COLUMN id SET DEFAULT nextval('public.facility_bookings_id_seq'::regclass);


--
-- Name: fdms_receipts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts ALTER COLUMN id SET DEFAULT nextval('public.fdms_receipts_id_seq'::regclass);


--
-- Name: fdms_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_settings ALTER COLUMN id SET DEFAULT nextval('public.fdms_settings_id_seq'::regclass);


--
-- Name: finance_budgets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_budgets ALTER COLUMN id SET DEFAULT nextval('public.finance_budgets_id_seq'::regclass);


--
-- Name: finance_chart_of_accounts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_chart_of_accounts ALTER COLUMN id SET DEFAULT nextval('public.finance_chart_of_accounts_id_seq'::regclass);


--
-- Name: finance_general_journal_headers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers ALTER COLUMN id SET DEFAULT nextval('public.finance_general_journal_headers_id_seq'::regclass);


--
-- Name: finance_general_journal_lines id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_lines ALTER COLUMN id SET DEFAULT nextval('public.finance_general_journal_lines_id_seq'::regclass);


--
-- Name: finance_general_ledger id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_ledger ALTER COLUMN id SET DEFAULT nextval('public.finance_general_ledger_id_seq'::regclass);


--
-- Name: finance_invoice_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoice_items ALTER COLUMN id SET DEFAULT nextval('public.finance_invoice_items_id_seq'::regclass);


--
-- Name: finance_invoices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoices ALTER COLUMN id SET DEFAULT nextval('public.finance_invoices_id_seq'::regclass);


--
-- Name: finance_payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments ALTER COLUMN id SET DEFAULT nextval('public.finance_payments_id_seq'::regclass);


--
-- Name: financial_reports id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.financial_reports ALTER COLUMN id SET DEFAULT nextval('public.financial_reports_id_seq'::regclass);


--
-- Name: fiscal_devices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fiscal_devices ALTER COLUMN id SET DEFAULT nextval('public.fiscal_devices_id_seq'::regclass);


--
-- Name: fixed_assets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fixed_assets ALTER COLUMN id SET DEFAULT nextval('public.fixed_assets_id_seq'::regclass);


--
-- Name: fleet_vehicles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fleet_vehicles ALTER COLUMN id SET DEFAULT nextval('public.fleet_vehicles_id_seq'::regclass);


--
-- Name: gate_takings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gate_takings ALTER COLUMN id SET DEFAULT nextval('public.gate_takings_id_seq'::regclass);


--
-- Name: general_ledger id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.general_ledger ALTER COLUMN id SET DEFAULT nextval('public.general_ledger_id_seq'::regclass);


--
-- Name: health_facilities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_facilities ALTER COLUMN id SET DEFAULT nextval('public.health_facilities_id_seq'::regclass);


--
-- Name: health_inspections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_inspections ALTER COLUMN id SET DEFAULT nextval('public.health_inspections_id_seq'::regclass);


--
-- Name: housing_allocations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_allocations ALTER COLUMN id SET DEFAULT nextval('public.housing_allocations_id_seq'::regclass);


--
-- Name: housing_applications id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_applications ALTER COLUMN id SET DEFAULT nextval('public.housing_applications_id_seq'::regclass);


--
-- Name: housing_properties id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_properties ALTER COLUMN id SET DEFAULT nextval('public.housing_properties_id_seq'::regclass);


--
-- Name: housing_waiting_list id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_waiting_list ALTER COLUMN id SET DEFAULT nextval('public.housing_waiting_list_id_seq'::regclass);


--
-- Name: hr_attendance id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_attendance ALTER COLUMN id SET DEFAULT nextval('public.hr_attendance_id_seq'::regclass);


--
-- Name: hr_employees id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees ALTER COLUMN id SET DEFAULT nextval('public.hr_employees_id_seq'::regclass);


--
-- Name: hr_payroll id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_payroll ALTER COLUMN id SET DEFAULT nextval('public.hr_payroll_id_seq'::regclass);


--
-- Name: infrastructure_assets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.infrastructure_assets ALTER COLUMN id SET DEFAULT nextval('public.infrastructure_assets_id_seq'::regclass);


--
-- Name: inventory_categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_categories ALTER COLUMN id SET DEFAULT nextval('public.inventory_categories_id_seq'::regclass);


--
-- Name: inventory_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items ALTER COLUMN id SET DEFAULT nextval('public.inventory_items_id_seq'::regclass);


--
-- Name: inventory_transactions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_transactions ALTER COLUMN id SET DEFAULT nextval('public.inventory_transactions_id_seq'::regclass);


--
-- Name: licensing_business_licenses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.licensing_business_licenses ALTER COLUMN id SET DEFAULT nextval('public.licensing_business_licenses_id_seq'::regclass);


--
-- Name: market_stalls id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.market_stalls ALTER COLUMN id SET DEFAULT nextval('public.market_stalls_id_seq'::regclass);


--
-- Name: markets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.markets ALTER COLUMN id SET DEFAULT nextval('public.markets_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: municipal_bills id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_bills ALTER COLUMN id SET DEFAULT nextval('public.municipal_bills_id_seq'::regclass);


--
-- Name: municipal_service_categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_service_categories ALTER COLUMN id SET DEFAULT nextval('public.municipal_service_categories_id_seq'::regclass);


--
-- Name: municipal_services id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_services ALTER COLUMN id SET DEFAULT nextval('public.municipal_services_id_seq'::regclass);


--
-- Name: offices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offices ALTER COLUMN id SET DEFAULT nextval('public.offices_id_seq'::regclass);


--
-- Name: parking_violations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_violations ALTER COLUMN id SET DEFAULT nextval('public.parking_violations_id_seq'::regclass);


--
-- Name: parking_zones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_zones ALTER COLUMN id SET DEFAULT nextval('public.parking_zones_id_seq'::regclass);


--
-- Name: payment_methods id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_methods ALTER COLUMN id SET DEFAULT nextval('public.payment_methods_id_seq'::regclass);


--
-- Name: payment_vouchers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers ALTER COLUMN id SET DEFAULT nextval('public.payment_vouchers_id_seq'::regclass);


--
-- Name: planning_applications id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.planning_applications ALTER COLUMN id SET DEFAULT nextval('public.planning_applications_id_seq'::regclass);


--
-- Name: pos_terminals id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pos_terminals ALTER COLUMN id SET DEFAULT nextval('public.pos_terminals_id_seq'::regclass);


--
-- Name: program_budgets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.program_budgets ALTER COLUMN id SET DEFAULT nextval('public.program_budgets_id_seq'::regclass);


--
-- Name: properties id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.properties ALTER COLUMN id SET DEFAULT nextval('public.properties_id_seq'::regclass);


--
-- Name: property_leases id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_leases ALTER COLUMN id SET DEFAULT nextval('public.property_leases_id_seq'::regclass);


--
-- Name: property_rates id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_rates ALTER COLUMN id SET DEFAULT nextval('public.property_rates_id_seq'::regclass);


--
-- Name: property_valuations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_valuations ALTER COLUMN id SET DEFAULT nextval('public.property_valuations_id_seq'::regclass);


--
-- Name: purchase_order_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_order_items ALTER COLUMN id SET DEFAULT nextval('public.purchase_order_items_id_seq'::regclass);


--
-- Name: purchase_orders id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders ALTER COLUMN id SET DEFAULT nextval('public.purchase_orders_id_seq'::regclass);


--
-- Name: revenue_collections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.revenue_collections ALTER COLUMN id SET DEFAULT nextval('public.revenue_collections_id_seq'::regclass);


--
-- Name: service_request_attachments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_request_attachments ALTER COLUMN id SET DEFAULT nextval('public.service_request_attachments_id_seq'::regclass);


--
-- Name: service_requests id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests ALTER COLUMN id SET DEFAULT nextval('public.service_requests_id_seq'::regclass);


--
-- Name: service_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_types ALTER COLUMN id SET DEFAULT nextval('public.service_types_id_seq'::regclass);


--
-- Name: stall_allocations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stall_allocations ALTER COLUMN id SET DEFAULT nextval('public.stall_allocations_id_seq'::regclass);


--
-- Name: suppliers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.suppliers ALTER COLUMN id SET DEFAULT nextval('public.suppliers_id_seq'::regclass);


--
-- Name: survey_projects id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.survey_projects ALTER COLUMN id SET DEFAULT nextval('public.survey_projects_id_seq'::regclass);


--
-- Name: tenants id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tenants ALTER COLUMN id SET DEFAULT nextval('public.tenants_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: utilities_connections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilities_connections ALTER COLUMN id SET DEFAULT nextval('public.utilities_connections_id_seq'::regclass);


--
-- Name: voucher_lines id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.voucher_lines ALTER COLUMN id SET DEFAULT nextval('public.voucher_lines_id_seq'::regclass);


--
-- Name: vouchers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers ALTER COLUMN id SET DEFAULT nextval('public.vouchers_id_seq'::regclass);


--
-- Name: waste_collection_routes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.waste_collection_routes ALTER COLUMN id SET DEFAULT nextval('public.waste_collection_routes_id_seq'::regclass);


--
-- Name: water_bills id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_bills ALTER COLUMN id SET DEFAULT nextval('public.water_bills_id_seq'::regclass);


--
-- Name: water_connections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections ALTER COLUMN id SET DEFAULT nextval('public.water_connections_id_seq'::regclass);


--
-- Name: water_meter_readings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_meter_readings ALTER COLUMN id SET DEFAULT nextval('public.water_meter_readings_id_seq'::regclass);


--
-- Name: zimbabwe_chart_of_accounts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.zimbabwe_chart_of_accounts ALTER COLUMN id SET DEFAULT nextval('public.zimbabwe_chart_of_accounts_id_seq'::regclass);


--
-- Name: ap_bills ap_bills_bill_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills
    ADD CONSTRAINT ap_bills_bill_number_unique UNIQUE (bill_number);


--
-- Name: ap_bills ap_bills_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills
    ADD CONSTRAINT ap_bills_pkey PRIMARY KEY (id);


--
-- Name: ap_vendors ap_vendors_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_vendors
    ADD CONSTRAINT ap_vendors_pkey PRIMARY KEY (id);


--
-- Name: ap_vendors ap_vendors_vendor_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_vendors
    ADD CONSTRAINT ap_vendors_vendor_number_unique UNIQUE (vendor_number);


--
-- Name: ar_invoices ar_invoices_invoice_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_invoices
    ADD CONSTRAINT ar_invoices_invoice_number_unique UNIQUE (invoice_number);


--
-- Name: ar_invoices ar_invoices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_invoices
    ADD CONSTRAINT ar_invoices_pkey PRIMARY KEY (id);


--
-- Name: ar_receipts ar_receipts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_pkey PRIMARY KEY (id);


--
-- Name: ar_receipts ar_receipts_receipt_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_receipt_number_unique UNIQUE (receipt_number);


--
-- Name: asset_categories asset_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_categories
    ADD CONSTRAINT asset_categories_pkey PRIMARY KEY (id);


--
-- Name: asset_depreciation_history asset_depreciation_history_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_depreciation_history
    ADD CONSTRAINT asset_depreciation_history_pkey PRIMARY KEY (id);


--
-- Name: asset_locations asset_locations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_locations
    ADD CONSTRAINT asset_locations_pkey PRIMARY KEY (id);


--
-- Name: bank_accounts bank_accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_accounts
    ADD CONSTRAINT bank_accounts_pkey PRIMARY KEY (id);


--
-- Name: bank_reconciliations bank_reconciliations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_pkey PRIMARY KEY (id);


--
-- Name: bank_reconciliations bank_reconciliations_reconciliation_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_reconciliation_number_unique UNIQUE (reconciliation_number);


--
-- Name: bank_statements bank_statements_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_statements
    ADD CONSTRAINT bank_statements_pkey PRIMARY KEY (id);


--
-- Name: bank_statements bank_statements_statement_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_statements
    ADD CONSTRAINT bank_statements_statement_number_unique UNIQUE (statement_number);


--
-- Name: bill_line_items bill_line_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_line_items
    ADD CONSTRAINT bill_line_items_pkey PRIMARY KEY (id);


--
-- Name: bill_payments bill_payments_payment_reference_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_payments
    ADD CONSTRAINT bill_payments_payment_reference_unique UNIQUE (payment_reference);


--
-- Name: bill_payments bill_payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_payments
    ADD CONSTRAINT bill_payments_pkey PRIMARY KEY (id);


--
-- Name: bill_reminders bill_reminders_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_reminders
    ADD CONSTRAINT bill_reminders_pkey PRIMARY KEY (id);


--
-- Name: budgets budgets_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT budgets_code_unique UNIQUE (code);


--
-- Name: budgets budgets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT budgets_pkey PRIMARY KEY (id);


--
-- Name: building_inspections building_inspections_inspection_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.building_inspections
    ADD CONSTRAINT building_inspections_inspection_number_unique UNIQUE (inspection_number);


--
-- Name: building_inspections building_inspections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.building_inspections
    ADD CONSTRAINT building_inspections_pkey PRIMARY KEY (id);


--
-- Name: burial_records burial_records_burial_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.burial_records
    ADD CONSTRAINT burial_records_burial_number_unique UNIQUE (burial_number);


--
-- Name: burial_records burial_records_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.burial_records
    ADD CONSTRAINT burial_records_pkey PRIMARY KEY (id);


--
-- Name: cash_transactions cash_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions
    ADD CONSTRAINT cash_transactions_pkey PRIMARY KEY (id);


--
-- Name: cashbook_entries cashbook_entries_entry_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cashbook_entries
    ADD CONSTRAINT cashbook_entries_entry_number_unique UNIQUE (entry_number);


--
-- Name: cashbook_entries cashbook_entries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cashbook_entries
    ADD CONSTRAINT cashbook_entries_pkey PRIMARY KEY (id);


--
-- Name: cemeteries cemeteries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemeteries
    ADD CONSTRAINT cemeteries_pkey PRIMARY KEY (id);


--
-- Name: cemetery_plots cemetery_plots_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemetery_plots
    ADD CONSTRAINT cemetery_plots_pkey PRIMARY KEY (id);


--
-- Name: cemetery_plots cemetery_plots_plot_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemetery_plots
    ADD CONSTRAINT cemetery_plots_plot_number_unique UNIQUE (plot_number);


--
-- Name: committee_committees committee_committees_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.committee_committees
    ADD CONSTRAINT committee_committees_pkey PRIMARY KEY (id);


--
-- Name: committee_meetings committee_meetings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.committee_meetings
    ADD CONSTRAINT committee_meetings_pkey PRIMARY KEY (id);


--
-- Name: communications communications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.communications
    ADD CONSTRAINT communications_pkey PRIMARY KEY (id);


--
-- Name: cost_centers cost_centers_cost_center_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cost_centers
    ADD CONSTRAINT cost_centers_cost_center_code_unique UNIQUE (cost_center_code);


--
-- Name: cost_centers cost_centers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cost_centers
    ADD CONSTRAINT cost_centers_pkey PRIMARY KEY (id);


--
-- Name: councils councils_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.councils
    ADD CONSTRAINT councils_code_unique UNIQUE (code);


--
-- Name: councils councils_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.councils
    ADD CONSTRAINT councils_pkey PRIMARY KEY (id);


--
-- Name: currencies currencies_currency_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.currencies
    ADD CONSTRAINT currencies_currency_code_unique UNIQUE (currency_code);


--
-- Name: currencies currencies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.currencies
    ADD CONSTRAINT currencies_pkey PRIMARY KEY (id);


--
-- Name: customer_accounts customer_accounts_account_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_accounts
    ADD CONSTRAINT customer_accounts_account_number_unique UNIQUE (account_number);


--
-- Name: customer_accounts customer_accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_accounts
    ADD CONSTRAINT customer_accounts_pkey PRIMARY KEY (id);


--
-- Name: customers customers_customer_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers
    ADD CONSTRAINT customers_customer_number_unique UNIQUE (customer_number);


--
-- Name: customers customers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers
    ADD CONSTRAINT customers_pkey PRIMARY KEY (id);


--
-- Name: debtor_transactions debtor_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtor_transactions
    ADD CONSTRAINT debtor_transactions_pkey PRIMARY KEY (id);


--
-- Name: debtors debtors_debtor_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtors
    ADD CONSTRAINT debtors_debtor_number_unique UNIQUE (debtor_number);


--
-- Name: debtors debtors_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtors
    ADD CONSTRAINT debtors_pkey PRIMARY KEY (id);


--
-- Name: departments departments_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_code_unique UNIQUE (code);


--
-- Name: departments departments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_pkey PRIMARY KEY (id);


--
-- Name: engineering_projects engineering_projects_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.engineering_projects
    ADD CONSTRAINT engineering_projects_pkey PRIMARY KEY (id);


--
-- Name: engineering_projects engineering_projects_project_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.engineering_projects
    ADD CONSTRAINT engineering_projects_project_number_unique UNIQUE (project_number);


--
-- Name: event_permits event_permits_permit_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.event_permits
    ADD CONSTRAINT event_permits_permit_number_unique UNIQUE (permit_number);


--
-- Name: event_permits event_permits_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.event_permits
    ADD CONSTRAINT event_permits_pkey PRIMARY KEY (id);


--
-- Name: exchange_rate_histories exchange_rate_histories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.exchange_rate_histories
    ADD CONSTRAINT exchange_rate_histories_pkey PRIMARY KEY (id);


--
-- Name: facilities facilities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facilities
    ADD CONSTRAINT facilities_pkey PRIMARY KEY (id);


--
-- Name: facility_bookings facility_bookings_booking_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facility_bookings
    ADD CONSTRAINT facility_bookings_booking_number_unique UNIQUE (booking_number);


--
-- Name: facility_bookings facility_bookings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facility_bookings
    ADD CONSTRAINT facility_bookings_pkey PRIMARY KEY (id);


--
-- Name: fdms_receipts fdms_receipts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_pkey PRIMARY KEY (id);


--
-- Name: fdms_receipts fdms_receipts_receipt_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_receipt_number_unique UNIQUE (receipt_number);


--
-- Name: fdms_settings fdms_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_settings
    ADD CONSTRAINT fdms_settings_pkey PRIMARY KEY (id);


--
-- Name: finance_budgets finance_budgets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_budgets
    ADD CONSTRAINT finance_budgets_pkey PRIMARY KEY (id);


--
-- Name: finance_chart_of_accounts finance_chart_of_accounts_account_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_chart_of_accounts
    ADD CONSTRAINT finance_chart_of_accounts_account_code_unique UNIQUE (account_code);


--
-- Name: finance_chart_of_accounts finance_chart_of_accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_chart_of_accounts
    ADD CONSTRAINT finance_chart_of_accounts_pkey PRIMARY KEY (id);


--
-- Name: finance_general_journal_headers finance_general_journal_headers_journal_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers
    ADD CONSTRAINT finance_general_journal_headers_journal_number_unique UNIQUE (journal_number);


--
-- Name: finance_general_journal_headers finance_general_journal_headers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers
    ADD CONSTRAINT finance_general_journal_headers_pkey PRIMARY KEY (id);


--
-- Name: finance_general_journal_lines finance_general_journal_lines_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_lines
    ADD CONSTRAINT finance_general_journal_lines_pkey PRIMARY KEY (id);


--
-- Name: finance_general_ledger finance_general_ledger_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_ledger
    ADD CONSTRAINT finance_general_ledger_pkey PRIMARY KEY (id);


--
-- Name: finance_general_ledger finance_general_ledger_transaction_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_ledger
    ADD CONSTRAINT finance_general_ledger_transaction_number_unique UNIQUE (transaction_number);


--
-- Name: finance_invoice_items finance_invoice_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoice_items
    ADD CONSTRAINT finance_invoice_items_pkey PRIMARY KEY (id);


--
-- Name: finance_invoices finance_invoices_invoice_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoices
    ADD CONSTRAINT finance_invoices_invoice_number_unique UNIQUE (invoice_number);


--
-- Name: finance_invoices finance_invoices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoices
    ADD CONSTRAINT finance_invoices_pkey PRIMARY KEY (id);


--
-- Name: finance_payments finance_payments_payment_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments
    ADD CONSTRAINT finance_payments_payment_number_unique UNIQUE (payment_number);


--
-- Name: finance_payments finance_payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments
    ADD CONSTRAINT finance_payments_pkey PRIMARY KEY (id);


--
-- Name: financial_reports financial_reports_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.financial_reports
    ADD CONSTRAINT financial_reports_pkey PRIMARY KEY (id);


--
-- Name: fiscal_devices fiscal_devices_device_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fiscal_devices
    ADD CONSTRAINT fiscal_devices_device_id_unique UNIQUE (device_id);


--
-- Name: fiscal_devices fiscal_devices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fiscal_devices
    ADD CONSTRAINT fiscal_devices_pkey PRIMARY KEY (id);


--
-- Name: fixed_assets fixed_assets_asset_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fixed_assets
    ADD CONSTRAINT fixed_assets_asset_number_unique UNIQUE (asset_number);


--
-- Name: fixed_assets fixed_assets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fixed_assets
    ADD CONSTRAINT fixed_assets_pkey PRIMARY KEY (id);


--
-- Name: fleet_vehicles fleet_vehicles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fleet_vehicles
    ADD CONSTRAINT fleet_vehicles_pkey PRIMARY KEY (id);


--
-- Name: fleet_vehicles fleet_vehicles_registration_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fleet_vehicles
    ADD CONSTRAINT fleet_vehicles_registration_number_unique UNIQUE (registration_number);


--
-- Name: fleet_vehicles fleet_vehicles_vehicle_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fleet_vehicles
    ADD CONSTRAINT fleet_vehicles_vehicle_number_unique UNIQUE (vehicle_number);


--
-- Name: gate_takings gate_takings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gate_takings
    ADD CONSTRAINT gate_takings_pkey PRIMARY KEY (id);


--
-- Name: general_ledger general_ledger_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.general_ledger
    ADD CONSTRAINT general_ledger_pkey PRIMARY KEY (id);


--
-- Name: health_facilities health_facilities_license_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_facilities
    ADD CONSTRAINT health_facilities_license_number_unique UNIQUE (license_number);


--
-- Name: health_facilities health_facilities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_facilities
    ADD CONSTRAINT health_facilities_pkey PRIMARY KEY (id);


--
-- Name: health_inspections health_inspections_inspection_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_inspections
    ADD CONSTRAINT health_inspections_inspection_number_unique UNIQUE (inspection_number);


--
-- Name: health_inspections health_inspections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_inspections
    ADD CONSTRAINT health_inspections_pkey PRIMARY KEY (id);


--
-- Name: housing_allocations housing_allocations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_allocations
    ADD CONSTRAINT housing_allocations_pkey PRIMARY KEY (id);


--
-- Name: housing_applications housing_applications_application_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_applications
    ADD CONSTRAINT housing_applications_application_number_unique UNIQUE (application_number);


--
-- Name: housing_applications housing_applications_id_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_applications
    ADD CONSTRAINT housing_applications_id_number_unique UNIQUE (id_number);


--
-- Name: housing_applications housing_applications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_applications
    ADD CONSTRAINT housing_applications_pkey PRIMARY KEY (id);


--
-- Name: housing_properties housing_properties_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_properties
    ADD CONSTRAINT housing_properties_pkey PRIMARY KEY (id);


--
-- Name: housing_properties housing_properties_property_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_properties
    ADD CONSTRAINT housing_properties_property_number_unique UNIQUE (property_number);


--
-- Name: housing_waiting_list housing_waiting_list_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_waiting_list
    ADD CONSTRAINT housing_waiting_list_pkey PRIMARY KEY (id);


--
-- Name: hr_attendance hr_attendance_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_attendance
    ADD CONSTRAINT hr_attendance_pkey PRIMARY KEY (id);


--
-- Name: hr_employees hr_employees_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees
    ADD CONSTRAINT hr_employees_email_unique UNIQUE (email);


--
-- Name: hr_employees hr_employees_employee_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees
    ADD CONSTRAINT hr_employees_employee_number_unique UNIQUE (employee_number);


--
-- Name: hr_employees hr_employees_id_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees
    ADD CONSTRAINT hr_employees_id_number_unique UNIQUE (id_number);


--
-- Name: hr_employees hr_employees_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees
    ADD CONSTRAINT hr_employees_pkey PRIMARY KEY (id);


--
-- Name: hr_payroll hr_payroll_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_payroll
    ADD CONSTRAINT hr_payroll_pkey PRIMARY KEY (id);


--
-- Name: infrastructure_assets infrastructure_assets_asset_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.infrastructure_assets
    ADD CONSTRAINT infrastructure_assets_asset_number_unique UNIQUE (asset_number);


--
-- Name: infrastructure_assets infrastructure_assets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.infrastructure_assets
    ADD CONSTRAINT infrastructure_assets_pkey PRIMARY KEY (id);


--
-- Name: inventory_categories inventory_categories_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_categories
    ADD CONSTRAINT inventory_categories_code_unique UNIQUE (code);


--
-- Name: inventory_categories inventory_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_categories
    ADD CONSTRAINT inventory_categories_pkey PRIMARY KEY (id);


--
-- Name: inventory_items inventory_items_item_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_item_code_unique UNIQUE (item_code);


--
-- Name: inventory_items inventory_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_pkey PRIMARY KEY (id);


--
-- Name: inventory_transactions inventory_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_transactions
    ADD CONSTRAINT inventory_transactions_pkey PRIMARY KEY (id);


--
-- Name: inventory_transactions inventory_transactions_transaction_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_transactions
    ADD CONSTRAINT inventory_transactions_transaction_number_unique UNIQUE (transaction_number);


--
-- Name: licensing_business_licenses licensing_business_licenses_license_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.licensing_business_licenses
    ADD CONSTRAINT licensing_business_licenses_license_number_unique UNIQUE (license_number);


--
-- Name: licensing_business_licenses licensing_business_licenses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.licensing_business_licenses
    ADD CONSTRAINT licensing_business_licenses_pkey PRIMARY KEY (id);


--
-- Name: market_stalls market_stalls_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.market_stalls
    ADD CONSTRAINT market_stalls_pkey PRIMARY KEY (id);


--
-- Name: markets markets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.markets
    ADD CONSTRAINT markets_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: municipal_bills municipal_bills_bill_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_bills
    ADD CONSTRAINT municipal_bills_bill_number_unique UNIQUE (bill_number);


--
-- Name: municipal_bills municipal_bills_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_bills
    ADD CONSTRAINT municipal_bills_pkey PRIMARY KEY (id);


--
-- Name: municipal_service_categories municipal_service_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_service_categories
    ADD CONSTRAINT municipal_service_categories_pkey PRIMARY KEY (id);


--
-- Name: municipal_services municipal_services_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_services
    ADD CONSTRAINT municipal_services_code_unique UNIQUE (code);


--
-- Name: municipal_services municipal_services_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_services
    ADD CONSTRAINT municipal_services_pkey PRIMARY KEY (id);


--
-- Name: offices offices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offices
    ADD CONSTRAINT offices_pkey PRIMARY KEY (id);


--
-- Name: parking_violations parking_violations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_violations
    ADD CONSTRAINT parking_violations_pkey PRIMARY KEY (id);


--
-- Name: parking_violations parking_violations_violation_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_violations
    ADD CONSTRAINT parking_violations_violation_number_unique UNIQUE (violation_number);


--
-- Name: parking_zones parking_zones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_zones
    ADD CONSTRAINT parking_zones_pkey PRIMARY KEY (id);


--
-- Name: parking_zones parking_zones_zone_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_zones
    ADD CONSTRAINT parking_zones_zone_code_unique UNIQUE (zone_code);


--
-- Name: payment_methods payment_methods_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_methods
    ADD CONSTRAINT payment_methods_code_unique UNIQUE (code);


--
-- Name: payment_methods payment_methods_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_methods
    ADD CONSTRAINT payment_methods_pkey PRIMARY KEY (id);


--
-- Name: payment_vouchers payment_vouchers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_pkey PRIMARY KEY (id);


--
-- Name: payment_vouchers payment_vouchers_voucher_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_voucher_number_unique UNIQUE (voucher_number);


--
-- Name: planning_applications planning_applications_application_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.planning_applications
    ADD CONSTRAINT planning_applications_application_number_unique UNIQUE (application_number);


--
-- Name: planning_applications planning_applications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.planning_applications
    ADD CONSTRAINT planning_applications_pkey PRIMARY KEY (id);


--
-- Name: pos_terminals pos_terminals_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pos_terminals
    ADD CONSTRAINT pos_terminals_pkey PRIMARY KEY (id);


--
-- Name: pos_terminals pos_terminals_terminal_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pos_terminals
    ADD CONSTRAINT pos_terminals_terminal_id_unique UNIQUE (terminal_id);


--
-- Name: program_budgets program_budgets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.program_budgets
    ADD CONSTRAINT program_budgets_pkey PRIMARY KEY (id);


--
-- Name: properties properties_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.properties
    ADD CONSTRAINT properties_pkey PRIMARY KEY (id);


--
-- Name: properties properties_property_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.properties
    ADD CONSTRAINT properties_property_number_unique UNIQUE (property_number);


--
-- Name: property_leases property_leases_lease_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_leases
    ADD CONSTRAINT property_leases_lease_number_unique UNIQUE (lease_number);


--
-- Name: property_leases property_leases_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_leases
    ADD CONSTRAINT property_leases_pkey PRIMARY KEY (id);


--
-- Name: property_rates property_rates_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_rates
    ADD CONSTRAINT property_rates_pkey PRIMARY KEY (id);


--
-- Name: property_valuations property_valuations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_valuations
    ADD CONSTRAINT property_valuations_pkey PRIMARY KEY (id);


--
-- Name: purchase_order_items purchase_order_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_order_items
    ADD CONSTRAINT purchase_order_items_pkey PRIMARY KEY (id);


--
-- Name: purchase_orders purchase_orders_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_pkey PRIMARY KEY (id);


--
-- Name: purchase_orders purchase_orders_po_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_po_number_unique UNIQUE (po_number);


--
-- Name: revenue_collections revenue_collections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.revenue_collections
    ADD CONSTRAINT revenue_collections_pkey PRIMARY KEY (id);


--
-- Name: revenue_collections revenue_collections_receipt_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.revenue_collections
    ADD CONSTRAINT revenue_collections_receipt_number_unique UNIQUE (receipt_number);


--
-- Name: service_request_attachments service_request_attachments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_request_attachments
    ADD CONSTRAINT service_request_attachments_pkey PRIMARY KEY (id);


--
-- Name: service_requests service_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests
    ADD CONSTRAINT service_requests_pkey PRIMARY KEY (id);


--
-- Name: service_requests service_requests_request_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests
    ADD CONSTRAINT service_requests_request_number_unique UNIQUE (request_number);


--
-- Name: service_types service_types_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_types
    ADD CONSTRAINT service_types_code_unique UNIQUE (code);


--
-- Name: service_types service_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_types
    ADD CONSTRAINT service_types_pkey PRIMARY KEY (id);


--
-- Name: stall_allocations stall_allocations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stall_allocations
    ADD CONSTRAINT stall_allocations_pkey PRIMARY KEY (id);


--
-- Name: suppliers suppliers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.suppliers
    ADD CONSTRAINT suppliers_pkey PRIMARY KEY (id);


--
-- Name: survey_projects survey_projects_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.survey_projects
    ADD CONSTRAINT survey_projects_pkey PRIMARY KEY (id);


--
-- Name: survey_projects survey_projects_project_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.survey_projects
    ADD CONSTRAINT survey_projects_project_number_unique UNIQUE (project_number);


--
-- Name: tenants tenants_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tenants
    ADD CONSTRAINT tenants_pkey PRIMARY KEY (id);


--
-- Name: tenants tenants_tenant_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tenants
    ADD CONSTRAINT tenants_tenant_number_unique UNIQUE (tenant_number);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_employee_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_employee_id_unique UNIQUE (employee_id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: utilities_connections utilities_connections_connection_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilities_connections
    ADD CONSTRAINT utilities_connections_connection_number_unique UNIQUE (connection_number);


--
-- Name: utilities_connections utilities_connections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilities_connections
    ADD CONSTRAINT utilities_connections_pkey PRIMARY KEY (id);


--
-- Name: voucher_lines voucher_lines_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.voucher_lines
    ADD CONSTRAINT voucher_lines_pkey PRIMARY KEY (id);


--
-- Name: vouchers vouchers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers
    ADD CONSTRAINT vouchers_pkey PRIMARY KEY (id);


--
-- Name: vouchers vouchers_voucher_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers
    ADD CONSTRAINT vouchers_voucher_number_unique UNIQUE (voucher_number);


--
-- Name: waste_collection_routes waste_collection_routes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.waste_collection_routes
    ADD CONSTRAINT waste_collection_routes_pkey PRIMARY KEY (id);


--
-- Name: waste_collection_routes waste_collection_routes_route_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.waste_collection_routes
    ADD CONSTRAINT waste_collection_routes_route_code_unique UNIQUE (route_code);


--
-- Name: water_bills water_bills_bill_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_bills
    ADD CONSTRAINT water_bills_bill_number_unique UNIQUE (bill_number);


--
-- Name: water_bills water_bills_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_bills
    ADD CONSTRAINT water_bills_pkey PRIMARY KEY (id);


--
-- Name: water_connections water_connections_connection_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections
    ADD CONSTRAINT water_connections_connection_number_unique UNIQUE (connection_number);


--
-- Name: water_connections water_connections_meter_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections
    ADD CONSTRAINT water_connections_meter_number_unique UNIQUE (meter_number);


--
-- Name: water_connections water_connections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections
    ADD CONSTRAINT water_connections_pkey PRIMARY KEY (id);


--
-- Name: water_meter_readings water_meter_readings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_meter_readings
    ADD CONSTRAINT water_meter_readings_pkey PRIMARY KEY (id);


--
-- Name: zimbabwe_chart_of_accounts zimbabwe_chart_of_accounts_account_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.zimbabwe_chart_of_accounts
    ADD CONSTRAINT zimbabwe_chart_of_accounts_account_code_unique UNIQUE (account_code);


--
-- Name: zimbabwe_chart_of_accounts zimbabwe_chart_of_accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.zimbabwe_chart_of_accounts
    ADD CONSTRAINT zimbabwe_chart_of_accounts_pkey PRIMARY KEY (id);


--
-- Name: asset_depreciation_history_fixed_asset_id_depreciation_year_dep; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX asset_depreciation_history_fixed_asset_id_depreciation_year_dep ON public.asset_depreciation_history USING btree (fixed_asset_id, depreciation_year, depreciation_month);


--
-- Name: bank_reconciliations_bank_account_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bank_reconciliations_bank_account_id_index ON public.bank_reconciliations USING btree (bank_account_id);


--
-- Name: bank_reconciliations_reconciliation_date_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bank_reconciliations_reconciliation_date_status_index ON public.bank_reconciliations USING btree (reconciliation_date, status);


--
-- Name: bank_statements_statement_date_bank_account_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bank_statements_statement_date_bank_account_id_index ON public.bank_statements USING btree (statement_date, bank_account_id);


--
-- Name: bill_line_items_bill_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_line_items_bill_id_index ON public.bill_line_items USING btree (bill_id);


--
-- Name: bill_line_items_service_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_line_items_service_id_index ON public.bill_line_items USING btree (service_id);


--
-- Name: bill_payments_bill_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_payments_bill_id_index ON public.bill_payments USING btree (bill_id);


--
-- Name: bill_payments_payment_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_payments_payment_date_index ON public.bill_payments USING btree (payment_date);


--
-- Name: bill_payments_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_payments_status_index ON public.bill_payments USING btree (status);


--
-- Name: bill_reminders_bill_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_reminders_bill_id_index ON public.bill_reminders USING btree (bill_id);


--
-- Name: bill_reminders_sent_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_reminders_sent_date_index ON public.bill_reminders USING btree (sent_date);


--
-- Name: cash_transactions_bank_account_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cash_transactions_bank_account_id_index ON public.cash_transactions USING btree (bank_account_id);


--
-- Name: cash_transactions_transaction_date_transaction_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cash_transactions_transaction_date_transaction_type_index ON public.cash_transactions USING btree (transaction_date, transaction_type);


--
-- Name: cashbook_entries_transaction_date_entry_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cashbook_entries_transaction_date_entry_type_index ON public.cashbook_entries USING btree (transaction_date, entry_type);


--
-- Name: customer_accounts_account_number_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX customer_accounts_account_number_index ON public.customer_accounts USING btree (account_number);


--
-- Name: customer_accounts_council_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX customer_accounts_council_id_index ON public.customer_accounts USING btree (council_id);


--
-- Name: customer_accounts_customer_name_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX customer_accounts_customer_name_index ON public.customer_accounts USING btree (customer_name);


--
-- Name: customer_accounts_is_active_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX customer_accounts_is_active_index ON public.customer_accounts USING btree (is_active);


--
-- Name: debtor_transactions_debtor_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX debtor_transactions_debtor_id_status_index ON public.debtor_transactions USING btree (debtor_id, status);


--
-- Name: debtor_transactions_due_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX debtor_transactions_due_date_index ON public.debtor_transactions USING btree (due_date);


--
-- Name: exchange_rate_histories_currency_code_effective_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX exchange_rate_histories_currency_code_effective_date_index ON public.exchange_rate_histories USING btree (currency_code, effective_date);


--
-- Name: fdms_receipts_fdms_transmitted_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fdms_receipts_fdms_transmitted_index ON public.fdms_receipts USING btree (fdms_transmitted);


--
-- Name: fdms_receipts_receipt_date_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fdms_receipts_receipt_date_status_index ON public.fdms_receipts USING btree (receipt_date, status);


--
-- Name: finance_general_journal_headers_journal_date_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX finance_general_journal_headers_journal_date_status_index ON public.finance_general_journal_headers USING btree (journal_date, status);


--
-- Name: finance_general_journal_headers_journal_number_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX finance_general_journal_headers_journal_number_index ON public.finance_general_journal_headers USING btree (journal_number);


--
-- Name: finance_general_journal_lines_journal_header_id_line_number_ind; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX finance_general_journal_lines_journal_header_id_line_number_ind ON public.finance_general_journal_lines USING btree (journal_header_id, line_number);


--
-- Name: financial_reports_report_type_report_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX financial_reports_report_type_report_date_index ON public.financial_reports USING btree (report_type, report_date);


--
-- Name: financial_reports_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX financial_reports_status_index ON public.financial_reports USING btree (status);


--
-- Name: fixed_assets_asset_category_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fixed_assets_asset_category_status_index ON public.fixed_assets USING btree (asset_category, status);


--
-- Name: municipal_bills_council_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX municipal_bills_council_id_index ON public.municipal_bills USING btree (council_id);


--
-- Name: municipal_bills_customer_account_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX municipal_bills_customer_account_id_index ON public.municipal_bills USING btree (customer_account_id);


--
-- Name: municipal_bills_due_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX municipal_bills_due_date_index ON public.municipal_bills USING btree (due_date);


--
-- Name: municipal_bills_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX municipal_bills_status_index ON public.municipal_bills USING btree (status);


--
-- Name: municipal_service_categories_is_active_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX municipal_service_categories_is_active_index ON public.municipal_service_categories USING btree (is_active);


--
-- Name: payment_methods_code_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_methods_code_index ON public.payment_methods USING btree (code);


--
-- Name: payment_methods_is_active_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_methods_is_active_index ON public.payment_methods USING btree (is_active);


--
-- Name: payment_vouchers_payment_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_vouchers_payment_date_index ON public.payment_vouchers USING btree (payment_date);


--
-- Name: payment_vouchers_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_vouchers_status_index ON public.payment_vouchers USING btree (status);


--
-- Name: payment_vouchers_voucher_number_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_vouchers_voucher_number_index ON public.payment_vouchers USING btree (voucher_number);


--
-- Name: program_budgets_budget_year_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX program_budgets_budget_year_status_index ON public.program_budgets USING btree (budget_year, status);


--
-- Name: purchase_orders_po_date_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX purchase_orders_po_date_status_index ON public.purchase_orders USING btree (po_date, status);


--
-- Name: purchase_orders_supplier_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX purchase_orders_supplier_id_index ON public.purchase_orders USING btree (supplier_id);


--
-- Name: suppliers_name_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX suppliers_name_status_index ON public.suppliers USING btree (name, status);


--
-- Name: voucher_lines_voucher_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX voucher_lines_voucher_id_index ON public.voucher_lines USING btree (voucher_id);


--
-- Name: vouchers_voucher_date_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX vouchers_voucher_date_status_index ON public.vouchers USING btree (voucher_date, status);


--
-- Name: zimbabwe_chart_of_accounts_account_type_account_category_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX zimbabwe_chart_of_accounts_account_type_account_category_index ON public.zimbabwe_chart_of_accounts USING btree (account_type, account_category);


--
-- Name: zimbabwe_chart_of_accounts_parent_account_code_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX zimbabwe_chart_of_accounts_parent_account_code_index ON public.zimbabwe_chart_of_accounts USING btree (parent_account_code);


--
-- Name: ap_bills ap_bills_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills
    ADD CONSTRAINT ap_bills_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: ap_bills ap_bills_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills
    ADD CONSTRAINT ap_bills_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: ap_bills ap_bills_vendor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills
    ADD CONSTRAINT ap_bills_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES public.ap_vendors(id) ON DELETE CASCADE;


--
-- Name: ar_invoices ar_invoices_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_invoices
    ADD CONSTRAINT ar_invoices_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: ar_receipts ar_receipts_ar_invoice_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_ar_invoice_id_foreign FOREIGN KEY (ar_invoice_id) REFERENCES public.ar_invoices(id) ON DELETE SET NULL;


--
-- Name: ar_receipts ar_receipts_bank_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_bank_account_id_foreign FOREIGN KEY (bank_account_id) REFERENCES public.bank_accounts(id) ON DELETE SET NULL;


--
-- Name: ar_receipts ar_receipts_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE SET NULL;


--
-- Name: ar_receipts ar_receipts_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: ar_receipts ar_receipts_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: ar_receipts ar_receipts_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE SET NULL;


--
-- Name: ar_receipts ar_receipts_office_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_office_id_foreign FOREIGN KEY (office_id) REFERENCES public.offices(id) ON DELETE SET NULL;


--
-- Name: asset_depreciation_history asset_depreciation_history_fixed_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_depreciation_history
    ADD CONSTRAINT asset_depreciation_history_fixed_asset_id_foreign FOREIGN KEY (fixed_asset_id) REFERENCES public.fixed_assets(id) ON DELETE CASCADE;


--
-- Name: bank_reconciliations bank_reconciliations_bank_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_bank_account_id_foreign FOREIGN KEY (bank_account_id) REFERENCES public.bank_accounts(id);


--
-- Name: bank_reconciliations bank_reconciliations_bank_statement_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_bank_statement_id_foreign FOREIGN KEY (bank_statement_id) REFERENCES public.bank_statements(id);


--
-- Name: bank_reconciliations bank_reconciliations_prepared_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_prepared_by_foreign FOREIGN KEY (prepared_by) REFERENCES public.users(id);


--
-- Name: bank_reconciliations bank_reconciliations_reviewed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_reviewed_by_foreign FOREIGN KEY (reviewed_by) REFERENCES public.users(id);


--
-- Name: bank_statements bank_statements_bank_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_statements
    ADD CONSTRAINT bank_statements_bank_account_id_foreign FOREIGN KEY (bank_account_id) REFERENCES public.bank_accounts(id);


--
-- Name: bill_line_items bill_line_items_bill_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_line_items
    ADD CONSTRAINT bill_line_items_bill_id_foreign FOREIGN KEY (bill_id) REFERENCES public.municipal_bills(id) ON DELETE CASCADE;


--
-- Name: bill_line_items bill_line_items_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_line_items
    ADD CONSTRAINT bill_line_items_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.municipal_services(id) ON DELETE CASCADE;


--
-- Name: bill_payments bill_payments_bill_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_payments
    ADD CONSTRAINT bill_payments_bill_id_foreign FOREIGN KEY (bill_id) REFERENCES public.municipal_bills(id) ON DELETE CASCADE;


--
-- Name: bill_payments bill_payments_payment_method_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_payments
    ADD CONSTRAINT bill_payments_payment_method_id_foreign FOREIGN KEY (payment_method_id) REFERENCES public.payment_methods(id) ON DELETE SET NULL;


--
-- Name: bill_reminders bill_reminders_bill_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_reminders
    ADD CONSTRAINT bill_reminders_bill_id_foreign FOREIGN KEY (bill_id) REFERENCES public.municipal_bills(id) ON DELETE CASCADE;


--
-- Name: budgets budgets_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT budgets_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: building_inspections building_inspections_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.building_inspections
    ADD CONSTRAINT building_inspections_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: burial_records burial_records_plot_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.burial_records
    ADD CONSTRAINT burial_records_plot_id_foreign FOREIGN KEY (plot_id) REFERENCES public.cemetery_plots(id) ON DELETE CASCADE;


--
-- Name: cash_transactions cash_transactions_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions
    ADD CONSTRAINT cash_transactions_account_id_foreign FOREIGN KEY (account_id) REFERENCES public.finance_chart_of_accounts(id);


--
-- Name: cash_transactions cash_transactions_bank_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions
    ADD CONSTRAINT cash_transactions_bank_account_id_foreign FOREIGN KEY (bank_account_id) REFERENCES public.bank_accounts(id);


--
-- Name: cash_transactions cash_transactions_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions
    ADD CONSTRAINT cash_transactions_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: cash_transactions cash_transactions_reconciliation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions
    ADD CONSTRAINT cash_transactions_reconciliation_id_foreign FOREIGN KEY (reconciliation_id) REFERENCES public.bank_reconciliations(id);


--
-- Name: cashbook_entries cashbook_entries_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cashbook_entries
    ADD CONSTRAINT cashbook_entries_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: cashbook_entries cashbook_entries_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cashbook_entries
    ADD CONSTRAINT cashbook_entries_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: cemetery_plots cemetery_plots_cemetery_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemetery_plots
    ADD CONSTRAINT cemetery_plots_cemetery_id_foreign FOREIGN KEY (cemetery_id) REFERENCES public.cemeteries(id) ON DELETE CASCADE;


--
-- Name: committee_meetings committee_meetings_committee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.committee_meetings
    ADD CONSTRAINT committee_meetings_committee_id_foreign FOREIGN KEY (committee_id) REFERENCES public.committee_committees(id) ON DELETE CASCADE;


--
-- Name: cost_centers cost_centers_manager_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cost_centers
    ADD CONSTRAINT cost_centers_manager_id_foreign FOREIGN KEY (manager_id) REFERENCES public.users(id);


--
-- Name: customer_accounts customer_accounts_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_accounts
    ADD CONSTRAINT customer_accounts_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE CASCADE;


--
-- Name: debtor_transactions debtor_transactions_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtor_transactions
    ADD CONSTRAINT debtor_transactions_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: debtor_transactions debtor_transactions_debtor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtor_transactions
    ADD CONSTRAINT debtor_transactions_debtor_id_foreign FOREIGN KEY (debtor_id) REFERENCES public.debtors(id) ON DELETE CASCADE;


--
-- Name: departments departments_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE CASCADE;


--
-- Name: exchange_rate_histories exchange_rate_histories_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.exchange_rate_histories
    ADD CONSTRAINT exchange_rate_histories_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: exchange_rate_histories exchange_rate_histories_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.exchange_rate_histories
    ADD CONSTRAINT exchange_rate_histories_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: facility_bookings facility_bookings_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facility_bookings
    ADD CONSTRAINT facility_bookings_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: facility_bookings facility_bookings_facility_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facility_bookings
    ADD CONSTRAINT facility_bookings_facility_id_foreign FOREIGN KEY (facility_id) REFERENCES public.facilities(id) ON DELETE CASCADE;


--
-- Name: fdms_receipts fdms_receipts_cashier_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_cashier_id_foreign FOREIGN KEY (cashier_id) REFERENCES public.users(id);


--
-- Name: fdms_receipts fdms_receipts_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: fdms_receipts fdms_receipts_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE SET NULL;


--
-- Name: fdms_receipts fdms_receipts_original_receipt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_original_receipt_id_foreign FOREIGN KEY (original_receipt_id) REFERENCES public.fdms_receipts(id) ON DELETE SET NULL;


--
-- Name: finance_budgets finance_budgets_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_budgets
    ADD CONSTRAINT finance_budgets_account_id_foreign FOREIGN KEY (account_id) REFERENCES public.finance_chart_of_accounts(id) ON DELETE CASCADE;


--
-- Name: finance_general_journal_headers finance_general_journal_headers_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers
    ADD CONSTRAINT finance_general_journal_headers_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id);


--
-- Name: finance_general_journal_headers finance_general_journal_headers_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers
    ADD CONSTRAINT finance_general_journal_headers_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: finance_general_journal_headers finance_general_journal_headers_posted_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers
    ADD CONSTRAINT finance_general_journal_headers_posted_by_foreign FOREIGN KEY (posted_by) REFERENCES public.users(id);


--
-- Name: finance_general_journal_lines finance_general_journal_lines_account_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_lines
    ADD CONSTRAINT finance_general_journal_lines_account_code_foreign FOREIGN KEY (account_code) REFERENCES public.finance_chart_of_accounts(account_code);


--
-- Name: finance_general_journal_lines finance_general_journal_lines_journal_header_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_lines
    ADD CONSTRAINT finance_general_journal_lines_journal_header_id_foreign FOREIGN KEY (journal_header_id) REFERENCES public.finance_general_journal_headers(id) ON DELETE CASCADE;


--
-- Name: finance_general_ledger finance_general_ledger_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_ledger
    ADD CONSTRAINT finance_general_ledger_account_id_foreign FOREIGN KEY (account_id) REFERENCES public.finance_chart_of_accounts(id) ON DELETE CASCADE;


--
-- Name: finance_general_ledger finance_general_ledger_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_ledger
    ADD CONSTRAINT finance_general_ledger_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: finance_invoice_items finance_invoice_items_invoice_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoice_items
    ADD CONSTRAINT finance_invoice_items_invoice_id_foreign FOREIGN KEY (invoice_id) REFERENCES public.finance_invoices(id) ON DELETE CASCADE;


--
-- Name: finance_invoices finance_invoices_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoices
    ADD CONSTRAINT finance_invoices_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: finance_payments finance_payments_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments
    ADD CONSTRAINT finance_payments_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: finance_payments finance_payments_invoice_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments
    ADD CONSTRAINT finance_payments_invoice_id_foreign FOREIGN KEY (invoice_id) REFERENCES public.finance_invoices(id);


--
-- Name: finance_payments finance_payments_processed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments
    ADD CONSTRAINT finance_payments_processed_by_foreign FOREIGN KEY (processed_by) REFERENCES public.users(id);


--
-- Name: financial_reports financial_reports_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.financial_reports
    ADD CONSTRAINT financial_reports_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: fixed_assets fixed_assets_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fixed_assets
    ADD CONSTRAINT fixed_assets_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: fleet_vehicles fleet_vehicles_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fleet_vehicles
    ADD CONSTRAINT fleet_vehicles_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE CASCADE;


--
-- Name: gate_takings gate_takings_facility_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gate_takings
    ADD CONSTRAINT gate_takings_facility_id_foreign FOREIGN KEY (facility_id) REFERENCES public.facilities(id) ON DELETE CASCADE;


--
-- Name: health_inspections health_inspections_facility_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_inspections
    ADD CONSTRAINT health_inspections_facility_id_foreign FOREIGN KEY (facility_id) REFERENCES public.health_facilities(id);


--
-- Name: housing_allocations housing_allocations_application_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_allocations
    ADD CONSTRAINT housing_allocations_application_id_foreign FOREIGN KEY (application_id) REFERENCES public.housing_applications(id) ON DELETE CASCADE;


--
-- Name: housing_allocations housing_allocations_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_allocations
    ADD CONSTRAINT housing_allocations_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.housing_properties(id) ON DELETE CASCADE;


--
-- Name: housing_waiting_list housing_waiting_list_application_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_waiting_list
    ADD CONSTRAINT housing_waiting_list_application_id_foreign FOREIGN KEY (application_id) REFERENCES public.housing_applications(id) ON DELETE CASCADE;


--
-- Name: hr_attendance hr_attendance_employee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_attendance
    ADD CONSTRAINT hr_attendance_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES public.hr_employees(id) ON DELETE CASCADE;


--
-- Name: hr_employees hr_employees_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees
    ADD CONSTRAINT hr_employees_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE CASCADE;


--
-- Name: hr_payroll hr_payroll_employee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_payroll
    ADD CONSTRAINT hr_payroll_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES public.hr_employees(id) ON DELETE CASCADE;


--
-- Name: inventory_items inventory_items_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.inventory_categories(id) ON DELETE CASCADE;


--
-- Name: inventory_transactions inventory_transactions_item_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_transactions
    ADD CONSTRAINT inventory_transactions_item_id_foreign FOREIGN KEY (item_id) REFERENCES public.inventory_items(id) ON DELETE CASCADE;


--
-- Name: inventory_transactions inventory_transactions_processed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_transactions
    ADD CONSTRAINT inventory_transactions_processed_by_foreign FOREIGN KEY (processed_by) REFERENCES public.users(id);


--
-- Name: market_stalls market_stalls_market_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.market_stalls
    ADD CONSTRAINT market_stalls_market_id_foreign FOREIGN KEY (market_id) REFERENCES public.markets(id) ON DELETE CASCADE;


--
-- Name: municipal_bills municipal_bills_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_bills
    ADD CONSTRAINT municipal_bills_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE CASCADE;


--
-- Name: municipal_bills municipal_bills_customer_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_bills
    ADD CONSTRAINT municipal_bills_customer_account_id_foreign FOREIGN KEY (customer_account_id) REFERENCES public.customer_accounts(id) ON DELETE CASCADE;


--
-- Name: municipal_services municipal_services_service_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_services
    ADD CONSTRAINT municipal_services_service_type_id_foreign FOREIGN KEY (service_type_id) REFERENCES public.service_types(id) ON DELETE CASCADE;


--
-- Name: offices offices_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offices
    ADD CONSTRAINT offices_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE CASCADE;


--
-- Name: offices offices_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offices
    ADD CONSTRAINT offices_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE SET NULL;


--
-- Name: parking_violations parking_violations_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_violations
    ADD CONSTRAINT parking_violations_zone_id_foreign FOREIGN KEY (zone_id) REFERENCES public.parking_zones(id) ON DELETE CASCADE;


--
-- Name: payment_vouchers payment_vouchers_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: payment_vouchers payment_vouchers_bank_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_bank_account_id_foreign FOREIGN KEY (bank_account_id) REFERENCES public.bank_accounts(id) ON DELETE SET NULL;


--
-- Name: payment_vouchers payment_vouchers_bill_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_bill_id_foreign FOREIGN KEY (bill_id) REFERENCES public.ap_bills(id) ON DELETE SET NULL;


--
-- Name: payment_vouchers payment_vouchers_requested_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_requested_by_foreign FOREIGN KEY (requested_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: payment_vouchers payment_vouchers_vendor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES public.ap_vendors(id) ON DELETE SET NULL;


--
-- Name: planning_applications planning_applications_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.planning_applications
    ADD CONSTRAINT planning_applications_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: pos_terminals pos_terminals_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pos_terminals
    ADD CONSTRAINT pos_terminals_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: program_budgets program_budgets_responsible_officer_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.program_budgets
    ADD CONSTRAINT program_budgets_responsible_officer_foreign FOREIGN KEY (responsible_officer) REFERENCES public.users(id);


--
-- Name: property_leases property_leases_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_leases
    ADD CONSTRAINT property_leases_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: property_rates property_rates_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_rates
    ADD CONSTRAINT property_rates_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: property_valuations property_valuations_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_valuations
    ADD CONSTRAINT property_valuations_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: purchase_order_items purchase_order_items_item_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_order_items
    ADD CONSTRAINT purchase_order_items_item_id_foreign FOREIGN KEY (item_id) REFERENCES public.inventory_items(id);


--
-- Name: purchase_order_items purchase_order_items_purchase_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_order_items
    ADD CONSTRAINT purchase_order_items_purchase_order_id_foreign FOREIGN KEY (purchase_order_id) REFERENCES public.purchase_orders(id) ON DELETE CASCADE;


--
-- Name: purchase_orders purchase_orders_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id);


--
-- Name: purchase_orders purchase_orders_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: purchase_orders purchase_orders_supplier_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_supplier_id_foreign FOREIGN KEY (supplier_id) REFERENCES public.suppliers(id);


--
-- Name: revenue_collections revenue_collections_collected_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.revenue_collections
    ADD CONSTRAINT revenue_collections_collected_by_foreign FOREIGN KEY (collected_by) REFERENCES public.users(id);


--
-- Name: revenue_collections revenue_collections_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.revenue_collections
    ADD CONSTRAINT revenue_collections_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: service_request_attachments service_request_attachments_service_request_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_request_attachments
    ADD CONSTRAINT service_request_attachments_service_request_id_foreign FOREIGN KEY (service_request_id) REFERENCES public.service_requests(id) ON DELETE CASCADE;


--
-- Name: service_requests service_requests_assigned_to_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests
    ADD CONSTRAINT service_requests_assigned_to_foreign FOREIGN KEY (assigned_to) REFERENCES public.users(id);


--
-- Name: service_requests service_requests_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests
    ADD CONSTRAINT service_requests_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: service_requests service_requests_service_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests
    ADD CONSTRAINT service_requests_service_type_id_foreign FOREIGN KEY (service_type_id) REFERENCES public.service_types(id) ON DELETE CASCADE;


--
-- Name: stall_allocations stall_allocations_stall_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stall_allocations
    ADD CONSTRAINT stall_allocations_stall_id_foreign FOREIGN KEY (stall_id) REFERENCES public.market_stalls(id) ON DELETE CASCADE;


--
-- Name: tenants tenants_allocation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tenants
    ADD CONSTRAINT tenants_allocation_id_foreign FOREIGN KEY (allocation_id) REFERENCES public.housing_allocations(id) ON DELETE CASCADE;


--
-- Name: users users_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE CASCADE;


--
-- Name: users users_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE SET NULL;


--
-- Name: users users_office_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_office_id_foreign FOREIGN KEY (office_id) REFERENCES public.offices(id) ON DELETE SET NULL;


--
-- Name: utilities_connections utilities_connections_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilities_connections
    ADD CONSTRAINT utilities_connections_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: utilities_connections utilities_connections_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilities_connections
    ADD CONSTRAINT utilities_connections_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: voucher_lines voucher_lines_voucher_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.voucher_lines
    ADD CONSTRAINT voucher_lines_voucher_id_foreign FOREIGN KEY (voucher_id) REFERENCES public.vouchers(id) ON DELETE CASCADE;


--
-- Name: vouchers vouchers_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers
    ADD CONSTRAINT vouchers_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: vouchers vouchers_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers
    ADD CONSTRAINT vouchers_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: vouchers vouchers_prepared_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers
    ADD CONSTRAINT vouchers_prepared_by_foreign FOREIGN KEY (prepared_by) REFERENCES public.users(id);


--
-- Name: waste_collection_routes waste_collection_routes_assigned_vehicle_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.waste_collection_routes
    ADD CONSTRAINT waste_collection_routes_assigned_vehicle_id_foreign FOREIGN KEY (assigned_vehicle_id) REFERENCES public.fleet_vehicles(id);


--
-- Name: water_bills water_bills_connection_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_bills
    ADD CONSTRAINT water_bills_connection_id_foreign FOREIGN KEY (connection_id) REFERENCES public.water_connections(id) ON DELETE CASCADE;


--
-- Name: water_connections water_connections_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections
    ADD CONSTRAINT water_connections_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: water_connections water_connections_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections
    ADD CONSTRAINT water_connections_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: water_meter_readings water_meter_readings_connection_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_meter_readings
    ADD CONSTRAINT water_meter_readings_connection_id_foreign FOREIGN KEY (connection_id) REFERENCES public.water_connections(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.9
-- Dumped by pg_dump version 16.9

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: ap_bills; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ap_bills (
    id bigint NOT NULL,
    bill_number character varying(255) NOT NULL,
    vendor_id bigint NOT NULL,
    vendor_invoice_number character varying(255),
    bill_date date NOT NULL,
    due_date date NOT NULL,
    description text,
    subtotal numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    tax_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    total_amount numeric(15,2) NOT NULL,
    amount_paid numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    balance_due numeric(15,2) NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    created_by bigint NOT NULL,
    approved_by bigint,
    approved_date timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT ap_bills_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'paid'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: ap_bills_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ap_bills_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ap_bills_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ap_bills_id_seq OWNED BY public.ap_bills.id;


--
-- Name: ap_vendors; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ap_vendors (
    id bigint NOT NULL,
    vendor_number character varying(255) NOT NULL,
    vendor_name character varying(255) NOT NULL,
    contact_person character varying(255),
    email character varying(255),
    phone character varying(255),
    address text,
    tax_number character varying(255),
    bank_name character varying(255),
    account_number character varying(255),
    payment_terms character varying(255),
    credit_limit numeric(15,2),
    is_active boolean DEFAULT true NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: ap_vendors_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ap_vendors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ap_vendors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ap_vendors_id_seq OWNED BY public.ap_vendors.id;


--
-- Name: ar_invoices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ar_invoices (
    id bigint NOT NULL,
    customer_id bigint NOT NULL,
    invoice_number character varying(255) NOT NULL,
    invoice_date date NOT NULL,
    due_date date NOT NULL,
    amount numeric(15,2) NOT NULL,
    balance_due numeric(15,2) NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    description text,
    terms character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT ar_invoices_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'sent'::character varying, 'paid'::character varying, 'overdue'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: ar_invoices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ar_invoices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ar_invoices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ar_invoices_id_seq OWNED BY public.ar_invoices.id;


--
-- Name: ar_receipts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ar_receipts (
    id bigint NOT NULL,
    receipt_number character varying(255) NOT NULL,
    customer_id bigint NOT NULL,
    ar_invoice_id bigint,
    receipt_date date NOT NULL,
    amount_received numeric(15,2) NOT NULL,
    payment_method character varying(255) NOT NULL,
    payment_reference character varying(255),
    bank_account_id bigint,
    notes text,
    council_id bigint,
    department_id bigint,
    office_id bigint,
    created_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: ar_receipts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ar_receipts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ar_receipts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ar_receipts_id_seq OWNED BY public.ar_receipts.id;


--
-- Name: asset_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.asset_categories (
    id bigint NOT NULL,
    category_name character varying(255) NOT NULL,
    description text,
    default_useful_life integer,
    depreciation_method character varying(255) DEFAULT 'straight_line'::character varying NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: asset_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.asset_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: asset_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.asset_categories_id_seq OWNED BY public.asset_categories.id;


--
-- Name: asset_depreciation_history; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.asset_depreciation_history (
    id bigint NOT NULL,
    fixed_asset_id bigint NOT NULL,
    depreciation_year integer NOT NULL,
    depreciation_month integer NOT NULL,
    depreciation_amount numeric(15,2) NOT NULL,
    accumulated_depreciation numeric(15,2) NOT NULL,
    book_value numeric(15,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: asset_depreciation_history_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.asset_depreciation_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: asset_depreciation_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.asset_depreciation_history_id_seq OWNED BY public.asset_depreciation_history.id;


--
-- Name: asset_locations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.asset_locations (
    id bigint NOT NULL,
    location_name character varying(255) NOT NULL,
    description text,
    building character varying(255),
    floor character varying(255),
    room character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: asset_locations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.asset_locations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: asset_locations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.asset_locations_id_seq OWNED BY public.asset_locations.id;


--
-- Name: bank_accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bank_accounts (
    id bigint NOT NULL,
    account_number character varying(255) NOT NULL,
    account_name character varying(255) NOT NULL,
    bank_name character varying(255) NOT NULL,
    branch_name character varying(255),
    account_code character varying(255),
    currency_code character varying(3) DEFAULT 'USD'::character varying NOT NULL,
    opening_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    current_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: bank_accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bank_accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bank_accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bank_accounts_id_seq OWNED BY public.bank_accounts.id;


--
-- Name: bank_reconciliations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bank_reconciliations (
    id bigint NOT NULL,
    reconciliation_number character varying(255) NOT NULL,
    bank_account_id bigint NOT NULL,
    bank_statement_id bigint,
    reconciliation_date date NOT NULL,
    statement_date date NOT NULL,
    statement_balance numeric(15,2) NOT NULL,
    book_balance numeric(15,2) NOT NULL,
    outstanding_deposits numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    outstanding_checks numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    bank_charges numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    interest_earned numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    adjusted_balance numeric(15,2) NOT NULL,
    variance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    prepared_by bigint NOT NULL,
    reviewed_by bigint,
    reviewed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT bank_reconciliations_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'in_progress'::character varying, 'reconciled'::character varying, 'discrepancy'::character varying])::text[])))
);


--
-- Name: bank_reconciliations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bank_reconciliations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bank_reconciliations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bank_reconciliations_id_seq OWNED BY public.bank_reconciliations.id;


--
-- Name: bank_statements; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bank_statements (
    id bigint NOT NULL,
    statement_number character varying(255) NOT NULL,
    bank_account_id bigint NOT NULL,
    statement_date date NOT NULL,
    period_start date NOT NULL,
    period_end date NOT NULL,
    opening_balance numeric(15,2) NOT NULL,
    closing_balance numeric(15,2) NOT NULL,
    file_path character varying(255),
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT bank_statements_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'processed'::character varying, 'reconciled'::character varying])::text[])))
);


--
-- Name: bank_statements_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bank_statements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bank_statements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bank_statements_id_seq OWNED BY public.bank_statements.id;


--
-- Name: bill_line_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bill_line_items (
    id bigint NOT NULL,
    bill_id bigint NOT NULL,
    service_id bigint NOT NULL,
    description character varying(255) NOT NULL,
    quantity numeric(10,2) NOT NULL,
    unit_rate numeric(10,2) NOT NULL,
    amount numeric(12,2) NOT NULL,
    tax_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: bill_line_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bill_line_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bill_line_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bill_line_items_id_seq OWNED BY public.bill_line_items.id;


--
-- Name: bill_payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bill_payments (
    id bigint NOT NULL,
    bill_id bigint NOT NULL,
    payment_reference character varying(255) NOT NULL,
    amount numeric(12,2) NOT NULL,
    payment_date date NOT NULL,
    payment_method_id bigint,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT bill_payments_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'completed'::character varying, 'failed'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: bill_payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bill_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bill_payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bill_payments_id_seq OWNED BY public.bill_payments.id;


--
-- Name: bill_reminders; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bill_reminders (
    id bigint NOT NULL,
    bill_id bigint NOT NULL,
    type character varying(255) NOT NULL,
    sent_date date NOT NULL,
    method character varying(255) DEFAULT 'email'::character varying NOT NULL,
    message text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT bill_reminders_type_check CHECK (((type)::text = ANY ((ARRAY['first_reminder'::character varying, 'second_reminder'::character varying, 'final_notice'::character varying])::text[])))
);


--
-- Name: bill_reminders_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bill_reminders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bill_reminders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bill_reminders_id_seq OWNED BY public.bill_reminders.id;


--
-- Name: budgets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.budgets (
    id bigint NOT NULL,
    budget_name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    fiscal_year character varying(255) NOT NULL,
    total_budget numeric(15,2) NOT NULL,
    total_spent numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    variance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    created_by bigint NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT budgets_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'approved'::character varying, 'active'::character varying, 'closed'::character varying])::text[])))
);


--
-- Name: budgets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.budgets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: budgets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.budgets_id_seq OWNED BY public.budgets.id;


--
-- Name: building_inspections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.building_inspections (
    id bigint NOT NULL,
    inspection_number character varying(255) NOT NULL,
    property_id bigint NOT NULL,
    inspection_type character varying(255) NOT NULL,
    inspection_date date NOT NULL,
    inspector_name character varying(255) NOT NULL,
    inspection_stage character varying(255) NOT NULL,
    findings text NOT NULL,
    result character varying(255) NOT NULL,
    defects_noted text,
    recommendations text,
    re_inspection_date date,
    status character varying(255) DEFAULT 'completed'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: building_inspections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.building_inspections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: building_inspections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.building_inspections_id_seq OWNED BY public.building_inspections.id;


--
-- Name: burial_records; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.burial_records (
    id bigint NOT NULL,
    burial_number character varying(255) NOT NULL,
    plot_id bigint NOT NULL,
    deceased_name character varying(255) NOT NULL,
    deceased_id_number character varying(255),
    date_of_birth date,
    date_of_death date NOT NULL,
    burial_date date NOT NULL,
    cause_of_death character varying(255),
    next_of_kin_name character varying(255) NOT NULL,
    next_of_kin_contact character varying(255) NOT NULL,
    undertaker character varying(255),
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: burial_records_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.burial_records_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: burial_records_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.burial_records_id_seq OWNED BY public.burial_records.id;


--
-- Name: cash_transactions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cash_transactions (
    id bigint NOT NULL,
    transaction_type character varying(255) NOT NULL,
    amount numeric(15,2) NOT NULL,
    description text NOT NULL,
    transaction_date date NOT NULL,
    account_id bigint NOT NULL,
    bank_account_id bigint,
    reference_number character varying(255),
    reconciled_at timestamp(0) without time zone,
    reconciliation_id bigint,
    created_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: cash_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cash_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cash_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cash_transactions_id_seq OWNED BY public.cash_transactions.id;


--
-- Name: cashbook_entries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cashbook_entries (
    id bigint NOT NULL,
    entry_number character varying(255) NOT NULL,
    entry_type character varying(255) NOT NULL,
    transaction_date date NOT NULL,
    reference_number character varying(255),
    description text NOT NULL,
    amount numeric(15,2) NOT NULL,
    currency_code character varying(3) NOT NULL,
    exchange_rate numeric(10,6) DEFAULT '1'::numeric NOT NULL,
    payment_method character varying(255) NOT NULL,
    bank_account_id character varying(255),
    account_code character varying(255) NOT NULL,
    created_by bigint NOT NULL,
    status character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT cashbook_entries_entry_type_check CHECK (((entry_type)::text = ANY (ARRAY[('receipt'::character varying)::text, ('payment'::character varying)::text]))),
    CONSTRAINT cashbook_entries_payment_method_check CHECK (((payment_method)::text = ANY (ARRAY[('cash'::character varying)::text, ('cheque'::character varying)::text, ('electronic'::character varying)::text, ('mobile'::character varying)::text]))),
    CONSTRAINT cashbook_entries_status_check CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('cleared'::character varying)::text, ('cancelled'::character varying)::text])))
);


--
-- Name: cashbook_entries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cashbook_entries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cashbook_entries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cashbook_entries_id_seq OWNED BY public.cashbook_entries.id;


--
-- Name: cemeteries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cemeteries (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    location character varying(255) NOT NULL,
    address text NOT NULL,
    total_area numeric(10,2) NOT NULL,
    total_plots integer NOT NULL,
    available_plots integer NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    sections json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: cemeteries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cemeteries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cemeteries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cemeteries_id_seq OWNED BY public.cemeteries.id;


--
-- Name: cemetery_plots; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cemetery_plots (
    id bigint NOT NULL,
    cemetery_id bigint NOT NULL,
    plot_number character varying(255) NOT NULL,
    section character varying(255) NOT NULL,
    row_number character varying(255) NOT NULL,
    plot_type character varying(255) NOT NULL,
    size_length numeric(8,2) NOT NULL,
    size_width numeric(8,2) NOT NULL,
    price numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'available'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: cemetery_plots_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cemetery_plots_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cemetery_plots_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cemetery_plots_id_seq OWNED BY public.cemetery_plots.id;


--
-- Name: committee_committees; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.committee_committees (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    type character varying(255) NOT NULL,
    established_date date NOT NULL,
    dissolution_date date,
    chairperson character varying(255) NOT NULL,
    secretary character varying(255) NOT NULL,
    meeting_schedule json,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: committee_committees_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.committee_committees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: committee_committees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.committee_committees_id_seq OWNED BY public.committee_committees.id;


--
-- Name: committee_meetings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.committee_meetings (
    id bigint NOT NULL,
    committee_id bigint NOT NULL,
    meeting_number character varying(255) NOT NULL,
    meeting_date date NOT NULL,
    start_time time(0) without time zone NOT NULL,
    end_time time(0) without time zone,
    venue character varying(255) NOT NULL,
    meeting_type character varying(255) NOT NULL,
    agenda text,
    minutes text,
    status character varying(255) DEFAULT 'scheduled'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: committee_meetings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.committee_meetings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: committee_meetings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.committee_meetings_id_seq OWNED BY public.committee_meetings.id;


--
-- Name: communications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.communications (
    id bigint NOT NULL,
    type character varying(255) NOT NULL,
    subject character varying(255),
    message text NOT NULL,
    sender character varying(255) NOT NULL,
    recipient character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    sent_at timestamp(0) without time zone,
    metadata json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: communications_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.communications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: communications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.communications_id_seq OWNED BY public.communications.id;


--
-- Name: cost_centers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cost_centers (
    id bigint NOT NULL,
    cost_center_code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    manager_id bigint,
    budget_allocation numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: cost_centers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cost_centers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cost_centers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cost_centers_id_seq OWNED BY public.cost_centers.id;


--
-- Name: councils; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.councils (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    province character varying(255) NOT NULL,
    region character varying(255) NOT NULL,
    address text,
    phone character varying(255),
    email character varying(255),
    website character varying(255),
    established_date date,
    mayor_name character varying(255),
    population integer,
    area_km2 numeric(10,2),
    contact_info text,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: councils_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.councils_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: councils_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.councils_id_seq OWNED BY public.councils.id;


--
-- Name: currencies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.currencies (
    id bigint NOT NULL,
    currency_code character varying(3) NOT NULL,
    currency_name character varying(255) NOT NULL,
    currency_symbol character varying(10) NOT NULL,
    exchange_rate numeric(10,6) NOT NULL,
    is_base_currency boolean DEFAULT false NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    decimal_places integer DEFAULT 2 NOT NULL,
    rounding_precision numeric(8,6) DEFAULT 0.01 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: currencies_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.currencies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: currencies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.currencies_id_seq OWNED BY public.currencies.id;


--
-- Name: customer_accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.customer_accounts (
    id bigint NOT NULL,
    account_number character varying(255) NOT NULL,
    account_type character varying(255) NOT NULL,
    customer_name character varying(255) NOT NULL,
    contact_person character varying(255),
    phone character varying(255),
    email character varying(255),
    physical_address text NOT NULL,
    postal_address text,
    id_number character varying(255),
    vat_number character varying(255),
    credit_limit numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    current_balance numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    council_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT customer_accounts_account_type_check CHECK (((account_type)::text = ANY ((ARRAY['individual'::character varying, 'business'::character varying, 'organization'::character varying])::text[])))
);


--
-- Name: customer_accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.customer_accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: customer_accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.customer_accounts_id_seq OWNED BY public.customer_accounts.id;


--
-- Name: customers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.customers (
    id bigint NOT NULL,
    customer_number character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255),
    phone character varying(255),
    address text,
    id_number character varying(255),
    date_of_birth date,
    gender character varying(255),
    customer_type character varying(255) DEFAULT 'individual'::character varying NOT NULL,
    business_registration_number character varying(255),
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT customers_customer_type_check CHECK (((customer_type)::text = ANY (ARRAY[('individual'::character varying)::text, ('business'::character varying)::text]))),
    CONSTRAINT customers_gender_check CHECK (((gender)::text = ANY (ARRAY[('male'::character varying)::text, ('female'::character varying)::text, ('other'::character varying)::text])))
);


--
-- Name: customers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.customers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: customers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.customers_id_seq OWNED BY public.customers.id;


--
-- Name: debtor_transactions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.debtor_transactions (
    id bigint NOT NULL,
    debtor_id bigint NOT NULL,
    transaction_number character varying(255) NOT NULL,
    transaction_type character varying(255) NOT NULL,
    transaction_date date NOT NULL,
    due_date date,
    description text NOT NULL,
    amount numeric(15,2) NOT NULL,
    currency_code character varying(3) NOT NULL,
    reference_number character varying(255),
    status character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT debtor_transactions_status_check CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('paid'::character varying)::text, ('overdue'::character varying)::text, ('written_off'::character varying)::text]))),
    CONSTRAINT debtor_transactions_transaction_type_check CHECK (((transaction_type)::text = ANY (ARRAY[('invoice'::character varying)::text, ('payment'::character varying)::text, ('credit_note'::character varying)::text, ('adjustment'::character varying)::text])))
);


--
-- Name: debtor_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.debtor_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: debtor_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.debtor_transactions_id_seq OWNED BY public.debtor_transactions.id;


--
-- Name: debtors; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.debtors (
    id bigint NOT NULL,
    debtor_number character varying(255) NOT NULL,
    debtor_name character varying(255) NOT NULL,
    debtor_type character varying(255) NOT NULL,
    contact_person character varying(255),
    phone character varying(255),
    email character varying(255),
    address text,
    credit_limit numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    payment_terms integer DEFAULT 30 NOT NULL,
    current_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: debtors_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.debtors_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: debtors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.debtors_id_seq OWNED BY public.debtors.id;


--
-- Name: departments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.departments (
    id bigint NOT NULL,
    council_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    head_name character varying(255),
    phone character varying(255),
    email character varying(255),
    module_access json,
    budget_allocation numeric(15,2),
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: departments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.departments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: departments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.departments_id_seq OWNED BY public.departments.id;


--
-- Name: engineering_projects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.engineering_projects (
    id bigint NOT NULL,
    project_number character varying(255) NOT NULL,
    project_name character varying(255) NOT NULL,
    project_type character varying(255) NOT NULL,
    description text NOT NULL,
    location text NOT NULL,
    estimated_cost numeric(15,2) NOT NULL,
    actual_cost numeric(15,2),
    start_date date NOT NULL,
    planned_completion_date date NOT NULL,
    actual_completion_date date,
    project_manager character varying(255) NOT NULL,
    contractor character varying(255),
    status character varying(255) DEFAULT 'planning'::character varying NOT NULL,
    completion_percentage integer DEFAULT 0 NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: engineering_projects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.engineering_projects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: engineering_projects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.engineering_projects_id_seq OWNED BY public.engineering_projects.id;


--
-- Name: event_permits; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.event_permits (
    id bigint NOT NULL,
    permit_number character varying(255) NOT NULL,
    event_name character varying(255) NOT NULL,
    organizer_name character varying(255) NOT NULL,
    organizer_contact character varying(255) NOT NULL,
    event_type character varying(255) NOT NULL,
    event_description text NOT NULL,
    venue_location text NOT NULL,
    event_date date NOT NULL,
    start_time time(0) without time zone NOT NULL,
    end_time time(0) without time zone NOT NULL,
    expected_attendance integer NOT NULL,
    permit_fee numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    conditions text,
    rejection_reason text,
    application_date date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: event_permits_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.event_permits_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: event_permits_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.event_permits_id_seq OWNED BY public.event_permits.id;


--
-- Name: exchange_rate_histories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.exchange_rate_histories (
    id bigint NOT NULL,
    currency_code character varying(3) NOT NULL,
    exchange_rate numeric(10,6) NOT NULL,
    effective_date date NOT NULL,
    created_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: exchange_rate_histories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.exchange_rate_histories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: exchange_rate_histories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.exchange_rate_histories_id_seq OWNED BY public.exchange_rate_histories.id;


--
-- Name: facilities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.facilities (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    location character varying(255) NOT NULL,
    capacity integer NOT NULL,
    description text,
    amenities json,
    hourly_rate numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    daily_rate numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'available'::character varying NOT NULL,
    operating_hours json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: facilities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.facilities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: facilities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.facilities_id_seq OWNED BY public.facilities.id;


--
-- Name: facility_bookings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.facility_bookings (
    id bigint NOT NULL,
    booking_number character varying(255) NOT NULL,
    facility_id bigint NOT NULL,
    customer_id bigint NOT NULL,
    event_name character varying(255) NOT NULL,
    event_description text,
    booking_date date NOT NULL,
    start_time time(0) without time zone NOT NULL,
    end_time time(0) without time zone NOT NULL,
    expected_attendees integer NOT NULL,
    total_cost numeric(10,2) NOT NULL,
    deposit_paid numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    special_requirements text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: facility_bookings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.facility_bookings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: facility_bookings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.facility_bookings_id_seq OWNED BY public.facility_bookings.id;


--
-- Name: fdms_receipts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fdms_receipts (
    id bigint NOT NULL,
    receipt_number character varying(255) NOT NULL,
    fiscal_receipt_number character varying(255),
    customer_id bigint,
    cashier_id bigint NOT NULL,
    receipt_date date NOT NULL,
    receipt_time timestamp(0) without time zone NOT NULL,
    payment_method character varying(255) NOT NULL,
    currency_code character varying(3) NOT NULL,
    exchange_rate numeric(10,6) DEFAULT '1'::numeric NOT NULL,
    subtotal_amount numeric(15,2) NOT NULL,
    tax_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    total_amount numeric(15,2) NOT NULL,
    amount_tendered numeric(15,2) NOT NULL,
    change_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    fiscal_device_id character varying(255),
    fiscal_signature text,
    qr_code text,
    verification_code character varying(255),
    fdms_transmitted boolean DEFAULT false NOT NULL,
    fdms_transmission_date timestamp(0) without time zone,
    fdms_response json,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    voided_at timestamp(0) without time zone,
    void_reason text,
    original_receipt_id bigint,
    items json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT fdms_receipts_payment_method_check CHECK (((payment_method)::text = ANY (ARRAY[('cash'::character varying)::text, ('card'::character varying)::text, ('mobile'::character varying)::text, ('bank_transfer'::character varying)::text, ('cheque'::character varying)::text]))),
    CONSTRAINT fdms_receipts_status_check CHECK (((status)::text = ANY (ARRAY[('active'::character varying)::text, ('voided'::character varying)::text, ('cancelled'::character varying)::text])))
);


--
-- Name: fdms_receipts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fdms_receipts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fdms_receipts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fdms_receipts_id_seq OWNED BY public.fdms_receipts.id;


--
-- Name: fdms_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fdms_settings (
    id bigint NOT NULL,
    operator_id character varying(255) NOT NULL,
    terminal_id character varying(255) NOT NULL,
    certificate_path character varying(255) NOT NULL,
    api_endpoint character varying(255) NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: fdms_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fdms_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fdms_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fdms_settings_id_seq OWNED BY public.fdms_settings.id;


--
-- Name: finance_budgets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_budgets (
    id bigint NOT NULL,
    budget_name character varying(255) NOT NULL,
    financial_year character varying(255) NOT NULL,
    account_id bigint NOT NULL,
    budgeted_amount numeric(15,2) NOT NULL,
    actual_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    variance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    period character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_budgets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_budgets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_budgets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_budgets_id_seq OWNED BY public.finance_budgets.id;


--
-- Name: finance_chart_of_accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_chart_of_accounts (
    id bigint NOT NULL,
    account_code character varying(255) NOT NULL,
    account_name character varying(255) NOT NULL,
    account_type character varying(255) NOT NULL,
    account_category character varying(255) NOT NULL,
    parent_account_id integer,
    description text,
    is_active boolean DEFAULT true NOT NULL,
    opening_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    current_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: finance_chart_of_accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_chart_of_accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_chart_of_accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_chart_of_accounts_id_seq OWNED BY public.finance_chart_of_accounts.id;


--
-- Name: finance_general_journal_headers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_general_journal_headers (
    id bigint NOT NULL,
    journal_number character varying(255) NOT NULL,
    journal_date date NOT NULL,
    reference character varying(255),
    description text NOT NULL,
    journal_type character varying(255) DEFAULT 'general'::character varying NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    total_debits numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    total_credits numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    created_by bigint NOT NULL,
    approved_by bigint,
    posted_by bigint,
    approved_at timestamp(0) without time zone,
    posted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT finance_general_journal_headers_journal_type_check CHECK (((journal_type)::text = ANY ((ARRAY['general'::character varying, 'recurring'::character varying, 'reversing'::character varying, 'closing'::character varying])::text[]))),
    CONSTRAINT finance_general_journal_headers_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'approved'::character varying, 'posted'::character varying])::text[])))
);


--
-- Name: finance_general_journal_headers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_general_journal_headers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_general_journal_headers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_general_journal_headers_id_seq OWNED BY public.finance_general_journal_headers.id;


--
-- Name: finance_general_journal_lines; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_general_journal_lines (
    id bigint NOT NULL,
    journal_header_id bigint NOT NULL,
    account_code character varying(255) NOT NULL,
    description text NOT NULL,
    debit_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    credit_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    line_number integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_general_journal_lines_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_general_journal_lines_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_general_journal_lines_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_general_journal_lines_id_seq OWNED BY public.finance_general_journal_lines.id;


--
-- Name: finance_general_ledger; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_general_ledger (
    id bigint NOT NULL,
    transaction_number character varying(255) NOT NULL,
    account_id bigint NOT NULL,
    transaction_date date NOT NULL,
    transaction_type character varying(255) NOT NULL,
    amount numeric(15,2) NOT NULL,
    description text NOT NULL,
    reference_number character varying(255),
    source_document character varying(255),
    created_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_general_ledger_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_general_ledger_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_general_ledger_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_general_ledger_id_seq OWNED BY public.finance_general_ledger.id;


--
-- Name: finance_invoice_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_invoice_items (
    id bigint NOT NULL,
    invoice_id bigint NOT NULL,
    description character varying(255) NOT NULL,
    quantity integer NOT NULL,
    unit_price numeric(10,2) NOT NULL,
    line_total numeric(15,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_invoice_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_invoice_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_invoice_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_invoice_items_id_seq OWNED BY public.finance_invoice_items.id;


--
-- Name: finance_invoices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_invoices (
    id bigint NOT NULL,
    invoice_number character varying(255) NOT NULL,
    customer_id bigint NOT NULL,
    invoice_date date NOT NULL,
    due_date date NOT NULL,
    subtotal numeric(15,2) NOT NULL,
    vat_amount numeric(15,2) NOT NULL,
    total_amount numeric(15,2) NOT NULL,
    amount_paid numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    balance numeric(15,2) NOT NULL,
    status character varying(255) DEFAULT 'unpaid'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_invoices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_invoices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_invoices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_invoices_id_seq OWNED BY public.finance_invoices.id;


--
-- Name: finance_payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.finance_payments (
    id bigint NOT NULL,
    payment_number character varying(255) NOT NULL,
    invoice_id bigint,
    customer_id bigint NOT NULL,
    payment_date date NOT NULL,
    amount numeric(15,2) NOT NULL,
    payment_method character varying(255) NOT NULL,
    reference_number character varying(255),
    notes text,
    processed_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: finance_payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.finance_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: finance_payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.finance_payments_id_seq OWNED BY public.finance_payments.id;


--
-- Name: financial_reports; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.financial_reports (
    id bigint NOT NULL,
    report_type character varying(255) NOT NULL,
    report_name character varying(255) NOT NULL,
    report_date date NOT NULL,
    report_data json,
    status character varying(255) DEFAULT 'generating'::character varying NOT NULL,
    created_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT financial_reports_status_check CHECK (((status)::text = ANY (ARRAY[('generating'::character varying)::text, ('generated'::character varying)::text, ('failed'::character varying)::text])))
);


--
-- Name: financial_reports_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.financial_reports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: financial_reports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.financial_reports_id_seq OWNED BY public.financial_reports.id;


--
-- Name: fiscal_devices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fiscal_devices (
    id bigint NOT NULL,
    device_id character varying(255) NOT NULL,
    device_name character varying(255) NOT NULL,
    device_type character varying(255) NOT NULL,
    serial_number character varying(255) NOT NULL,
    manufacturer character varying(255) NOT NULL,
    firmware_version character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    last_sync timestamp(0) without time zone,
    configuration json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: fiscal_devices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fiscal_devices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fiscal_devices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fiscal_devices_id_seq OWNED BY public.fiscal_devices.id;


--
-- Name: fixed_assets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fixed_assets (
    id bigint NOT NULL,
    asset_number character varying(255) NOT NULL,
    asset_name character varying(255) NOT NULL,
    asset_description text,
    asset_category character varying(255) NOT NULL,
    asset_location character varying(255),
    acquisition_date date NOT NULL,
    acquisition_cost numeric(15,2) NOT NULL,
    currency_code character varying(3) NOT NULL,
    depreciation_rate numeric(5,2) NOT NULL,
    depreciation_method character varying(255) NOT NULL,
    useful_life_years integer NOT NULL,
    residual_value numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    accumulated_depreciation numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    current_value numeric(15,2) NOT NULL,
    custodian character varying(255),
    condition character varying(255) DEFAULT 'good'::character varying NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    disposal_date date,
    disposal_amount numeric(15,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT fixed_assets_condition_check CHECK (((condition)::text = ANY (ARRAY[('excellent'::character varying)::text, ('good'::character varying)::text, ('fair'::character varying)::text, ('poor'::character varying)::text]))),
    CONSTRAINT fixed_assets_depreciation_method_check CHECK (((depreciation_method)::text = ANY (ARRAY[('straight_line'::character varying)::text, ('reducing_balance'::character varying)::text, ('units_production'::character varying)::text]))),
    CONSTRAINT fixed_assets_status_check CHECK (((status)::text = ANY (ARRAY[('active'::character varying)::text, ('disposed'::character varying)::text, ('written_off'::character varying)::text])))
);


--
-- Name: fixed_assets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fixed_assets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fixed_assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fixed_assets_id_seq OWNED BY public.fixed_assets.id;


--
-- Name: fleet_vehicles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fleet_vehicles (
    id bigint NOT NULL,
    vehicle_number character varying(255) NOT NULL,
    registration_number character varying(255) NOT NULL,
    make character varying(255) NOT NULL,
    model character varying(255) NOT NULL,
    year integer NOT NULL,
    vehicle_type character varying(255) NOT NULL,
    department_id bigint NOT NULL,
    assigned_driver character varying(255),
    purchase_cost numeric(15,2),
    purchase_date date,
    current_odometer integer DEFAULT 0 NOT NULL,
    license_expiry_date date NOT NULL,
    insurance_expiry_date date NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: fleet_vehicles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fleet_vehicles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fleet_vehicles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fleet_vehicles_id_seq OWNED BY public.fleet_vehicles.id;


--
-- Name: gate_takings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gate_takings (
    id bigint NOT NULL,
    facility_id bigint NOT NULL,
    date date NOT NULL,
    time_period_start time(0) without time zone NOT NULL,
    time_period_end time(0) without time zone NOT NULL,
    adult_tickets integer NOT NULL,
    adult_price numeric(8,2) NOT NULL,
    child_tickets integer NOT NULL,
    child_price numeric(8,2) NOT NULL,
    senior_tickets integer NOT NULL,
    senior_price numeric(8,2) NOT NULL,
    total_amount numeric(10,2) NOT NULL,
    collected_by character varying(255) NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gate_takings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gate_takings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gate_takings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gate_takings_id_seq OWNED BY public.gate_takings.id;


--
-- Name: general_ledger; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.general_ledger (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: general_ledger_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.general_ledger_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: general_ledger_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.general_ledger_id_seq OWNED BY public.general_ledger.id;


--
-- Name: health_facilities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.health_facilities (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    address text NOT NULL,
    contact_person character varying(255) NOT NULL,
    phone character varying(255) NOT NULL,
    email character varying(255),
    license_number character varying(255) NOT NULL,
    license_expiry_date date NOT NULL,
    services_offered json,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: health_facilities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.health_facilities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: health_facilities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.health_facilities_id_seq OWNED BY public.health_facilities.id;


--
-- Name: health_inspections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.health_inspections (
    id bigint NOT NULL,
    inspection_number character varying(255) NOT NULL,
    facility_id bigint,
    inspection_type character varying(255) NOT NULL,
    inspection_date date NOT NULL,
    inspector_name character varying(255) NOT NULL,
    findings text NOT NULL,
    compliance_status character varying(255) NOT NULL,
    recommendations text,
    follow_up_date date,
    status character varying(255) DEFAULT 'completed'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: health_inspections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.health_inspections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: health_inspections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.health_inspections_id_seq OWNED BY public.health_inspections.id;


--
-- Name: housing_allocations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.housing_allocations (
    id bigint NOT NULL,
    application_id bigint NOT NULL,
    property_id bigint NOT NULL,
    allocation_date date NOT NULL,
    lease_start_date date NOT NULL,
    lease_end_date date NOT NULL,
    monthly_rent numeric(10,2) NOT NULL,
    deposit_amount numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    terms_conditions text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: housing_allocations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.housing_allocations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: housing_allocations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.housing_allocations_id_seq OWNED BY public.housing_allocations.id;


--
-- Name: housing_applications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.housing_applications (
    id bigint NOT NULL,
    application_number character varying(255) NOT NULL,
    applicant_name character varying(255) NOT NULL,
    id_number character varying(255) NOT NULL,
    email character varying(255),
    phone character varying(255) NOT NULL,
    current_address text NOT NULL,
    household_size integer NOT NULL,
    monthly_income numeric(10,2) NOT NULL,
    employment_status character varying(255) NOT NULL,
    preferred_area character varying(255),
    property_type_preference character varying(255),
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    applied_at timestamp(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: housing_applications_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.housing_applications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: housing_applications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.housing_applications_id_seq OWNED BY public.housing_applications.id;


--
-- Name: housing_properties; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.housing_properties (
    id bigint NOT NULL,
    property_number character varying(255) NOT NULL,
    property_type character varying(255) NOT NULL,
    address character varying(255) NOT NULL,
    suburb character varying(255) NOT NULL,
    city character varying(255) NOT NULL,
    postal_code character varying(255) NOT NULL,
    bedrooms integer NOT NULL,
    bathrooms integer NOT NULL,
    size_sqm numeric(8,2),
    rental_amount numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'available'::character varying NOT NULL,
    description text,
    amenities json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: housing_properties_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.housing_properties_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: housing_properties_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.housing_properties_id_seq OWNED BY public.housing_properties.id;


--
-- Name: housing_waiting_list; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.housing_waiting_list (
    id bigint NOT NULL,
    application_id bigint NOT NULL,
    "position" integer NOT NULL,
    category character varying(255) NOT NULL,
    priority_score integer NOT NULL,
    date_added date NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: housing_waiting_list_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.housing_waiting_list_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: housing_waiting_list_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.housing_waiting_list_id_seq OWNED BY public.housing_waiting_list.id;


--
-- Name: hr_attendance; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hr_attendance (
    id bigint NOT NULL,
    employee_id bigint NOT NULL,
    date date NOT NULL,
    time_in time(0) without time zone,
    time_out time(0) without time zone,
    hours_worked numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    overtime_hours numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: hr_attendance_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hr_attendance_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hr_attendance_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hr_attendance_id_seq OWNED BY public.hr_attendance.id;


--
-- Name: hr_employees; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hr_employees (
    id bigint NOT NULL,
    employee_number character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    id_number character varying(255) NOT NULL,
    email character varying(255),
    phone character varying(255) NOT NULL,
    address text NOT NULL,
    date_of_birth date NOT NULL,
    gender character varying(255) NOT NULL,
    department_id bigint NOT NULL,
    "position" character varying(255) NOT NULL,
    employment_type character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date,
    basic_salary numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: hr_employees_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hr_employees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hr_employees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hr_employees_id_seq OWNED BY public.hr_employees.id;


--
-- Name: hr_payroll; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hr_payroll (
    id bigint NOT NULL,
    employee_id bigint NOT NULL,
    pay_period character varying(255) NOT NULL,
    pay_date date NOT NULL,
    basic_salary numeric(10,2) NOT NULL,
    overtime_amount numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    allowances numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    gross_salary numeric(10,2) NOT NULL,
    tax_deduction numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    uif_deduction numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    other_deductions numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    net_salary numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: hr_payroll_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hr_payroll_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hr_payroll_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hr_payroll_id_seq OWNED BY public.hr_payroll.id;


--
-- Name: infrastructure_assets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.infrastructure_assets (
    id bigint NOT NULL,
    asset_number character varying(255) NOT NULL,
    asset_name character varying(255) NOT NULL,
    asset_type character varying(255) NOT NULL,
    category character varying(255) NOT NULL,
    location text NOT NULL,
    description text,
    installation_date date,
    original_cost numeric(15,2),
    current_value numeric(15,2),
    condition character varying(255) NOT NULL,
    last_inspection_date date,
    next_inspection_date date,
    expected_life_years integer,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: infrastructure_assets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.infrastructure_assets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: infrastructure_assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.infrastructure_assets_id_seq OWNED BY public.infrastructure_assets.id;


--
-- Name: inventory_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_categories (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    parent_category_id integer,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: inventory_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.inventory_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: inventory_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.inventory_categories_id_seq OWNED BY public.inventory_categories.id;


--
-- Name: inventory_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_items (
    id bigint NOT NULL,
    item_code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    category_id bigint NOT NULL,
    unit_of_measure character varying(255) NOT NULL,
    unit_cost numeric(10,2) NOT NULL,
    current_stock integer DEFAULT 0 NOT NULL,
    minimum_stock_level integer DEFAULT 0 NOT NULL,
    maximum_stock_level integer DEFAULT 0 NOT NULL,
    location character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: inventory_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.inventory_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: inventory_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.inventory_items_id_seq OWNED BY public.inventory_items.id;


--
-- Name: inventory_transactions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_transactions (
    id bigint NOT NULL,
    transaction_number character varying(255) NOT NULL,
    item_id bigint NOT NULL,
    transaction_type character varying(255) NOT NULL,
    quantity integer NOT NULL,
    unit_cost numeric(10,2) NOT NULL,
    total_cost numeric(15,2) NOT NULL,
    transaction_date date NOT NULL,
    reference_document character varying(255),
    notes text,
    processed_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: inventory_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.inventory_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: inventory_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.inventory_transactions_id_seq OWNED BY public.inventory_transactions.id;


--
-- Name: licensing_business_licenses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.licensing_business_licenses (
    id bigint NOT NULL,
    license_number character varying(255) NOT NULL,
    business_name character varying(255) NOT NULL,
    business_type character varying(255) NOT NULL,
    owner_name character varying(255) NOT NULL,
    owner_id_number character varying(255) NOT NULL,
    business_address text NOT NULL,
    contact_phone character varying(255) NOT NULL,
    contact_email character varying(255),
    issue_date date NOT NULL,
    expiry_date date NOT NULL,
    license_fee numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    conditions text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: licensing_business_licenses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.licensing_business_licenses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: licensing_business_licenses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.licensing_business_licenses_id_seq OWNED BY public.licensing_business_licenses.id;


--
-- Name: market_stalls; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.market_stalls (
    id bigint NOT NULL,
    market_id bigint NOT NULL,
    stall_number character varying(255) NOT NULL,
    section character varying(255) NOT NULL,
    size_sqm numeric(8,2) NOT NULL,
    daily_rate numeric(8,2) NOT NULL,
    monthly_rate numeric(8,2) NOT NULL,
    stall_type character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'available'::character varying NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: market_stalls_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.market_stalls_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: market_stalls_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.market_stalls_id_seq OWNED BY public.market_stalls.id;


--
-- Name: markets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.markets (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    address text NOT NULL,
    market_type character varying(255) NOT NULL,
    total_stalls integer NOT NULL,
    occupied_stalls integer DEFAULT 0 NOT NULL,
    operating_days json NOT NULL,
    opening_time time(0) without time zone NOT NULL,
    closing_time time(0) without time zone NOT NULL,
    market_manager character varying(255) NOT NULL,
    contact_phone character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: markets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.markets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: markets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.markets_id_seq OWNED BY public.markets.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: municipal_bills; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.municipal_bills (
    id bigint NOT NULL,
    bill_number character varying(255) NOT NULL,
    customer_account_id bigint NOT NULL,
    council_id bigint NOT NULL,
    bill_date date NOT NULL,
    due_date date NOT NULL,
    billing_period character varying(255) NOT NULL,
    subtotal numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    tax_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    discount_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    penalty_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    total_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    paid_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    outstanding_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    sent_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT municipal_bills_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'pending'::character varying, 'sent'::character varying, 'paid'::character varying, 'overdue'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: municipal_bills_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.municipal_bills_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: municipal_bills_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.municipal_bills_id_seq OWNED BY public.municipal_bills.id;


--
-- Name: municipal_service_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.municipal_service_categories (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: municipal_service_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.municipal_service_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: municipal_service_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.municipal_service_categories_id_seq OWNED BY public.municipal_service_categories.id;


--
-- Name: municipal_services; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.municipal_services (
    id bigint NOT NULL,
    service_type_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    fee numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    processing_days integer DEFAULT 1 NOT NULL,
    required_documents json,
    department character varying(255),
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: municipal_services_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.municipal_services_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: municipal_services_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.municipal_services_id_seq OWNED BY public.municipal_services.id;


--
-- Name: offices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.offices (
    id bigint NOT NULL,
    council_id bigint NOT NULL,
    department_id bigint,
    name character varying(255) NOT NULL,
    location character varying(255),
    address text,
    phone character varying(255),
    email character varying(255),
    office_type character varying(255),
    capacity integer,
    facilities json,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: offices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.offices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: offices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.offices_id_seq OWNED BY public.offices.id;


--
-- Name: parking_violations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.parking_violations (
    id bigint NOT NULL,
    violation_number character varying(255) NOT NULL,
    vehicle_registration character varying(255) NOT NULL,
    zone_id bigint NOT NULL,
    violation_type character varying(255) NOT NULL,
    violation_datetime timestamp(0) without time zone NOT NULL,
    officer_name character varying(255) NOT NULL,
    fine_amount numeric(8,2) NOT NULL,
    status character varying(255) DEFAULT 'unpaid'::character varying NOT NULL,
    due_date date NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: parking_violations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.parking_violations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: parking_violations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.parking_violations_id_seq OWNED BY public.parking_violations.id;


--
-- Name: parking_zones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.parking_zones (
    id bigint NOT NULL,
    zone_name character varying(255) NOT NULL,
    zone_code character varying(255) NOT NULL,
    description text NOT NULL,
    hourly_rate numeric(8,2) NOT NULL,
    daily_rate numeric(8,2) NOT NULL,
    max_parking_hours integer NOT NULL,
    operating_hours json NOT NULL,
    restricted_days json,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: parking_zones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.parking_zones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: parking_zones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.parking_zones_id_seq OWNED BY public.parking_zones.id;


--
-- Name: payment_methods; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.payment_methods (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    is_active boolean DEFAULT true NOT NULL,
    transaction_fee_percentage numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    transaction_fee_fixed numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    configuration json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: payment_methods_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.payment_methods_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payment_methods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.payment_methods_id_seq OWNED BY public.payment_methods.id;


--
-- Name: payment_vouchers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.payment_vouchers (
    id bigint NOT NULL,
    voucher_number character varying(255) NOT NULL,
    bank_account_id bigint,
    payee_name character varying(255) NOT NULL,
    payee_address text,
    amount numeric(15,2) NOT NULL,
    currency character varying(3) DEFAULT 'USD'::character varying NOT NULL,
    payment_date date NOT NULL,
    payment_method character varying(255) NOT NULL,
    purpose character varying(255) NOT NULL,
    description text,
    reference_number character varying(255),
    priority character varying(255) DEFAULT 'normal'::character varying NOT NULL,
    invoice_number character varying(255),
    due_date date,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    requested_by bigint,
    approved_by bigint,
    approved_at timestamp(0) without time zone,
    approval_notes text,
    vendor_id bigint,
    bill_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT payment_vouchers_priority_check CHECK (((priority)::text = ANY ((ARRAY['low'::character varying, 'normal'::character varying, 'high'::character varying, 'urgent'::character varying])::text[]))),
    CONSTRAINT payment_vouchers_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'pending_approval'::character varying, 'approved'::character varying, 'paid'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: payment_vouchers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.payment_vouchers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payment_vouchers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.payment_vouchers_id_seq OWNED BY public.payment_vouchers.id;


--
-- Name: planning_applications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.planning_applications (
    id bigint NOT NULL,
    application_number character varying(255) NOT NULL,
    property_id bigint NOT NULL,
    applicant_name character varying(255) NOT NULL,
    applicant_contact character varying(255) NOT NULL,
    application_type character varying(255) NOT NULL,
    description text NOT NULL,
    proposed_development text NOT NULL,
    estimated_cost numeric(15,2),
    application_date date NOT NULL,
    target_decision_date date,
    status character varying(255) DEFAULT 'submitted'::character varying NOT NULL,
    conditions text,
    rejection_reason text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: planning_applications_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.planning_applications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: planning_applications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.planning_applications_id_seq OWNED BY public.planning_applications.id;


--
-- Name: pos_terminals; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pos_terminals (
    id bigint NOT NULL,
    terminal_id character varying(255) NOT NULL,
    terminal_name character varying(255) NOT NULL,
    location character varying(255) NOT NULL,
    serial_number character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    configuration json,
    created_by bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: pos_terminals_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.pos_terminals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pos_terminals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.pos_terminals_id_seq OWNED BY public.pos_terminals.id;


--
-- Name: program_budgets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.program_budgets (
    id bigint NOT NULL,
    program_code character varying(255) NOT NULL,
    program_name character varying(255) NOT NULL,
    program_description text,
    budget_year integer NOT NULL,
    allocated_amount numeric(15,2) NOT NULL,
    committed_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    actual_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) NOT NULL,
    responsible_officer bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT program_budgets_status_check CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('approved'::character varying)::text, ('active'::character varying)::text, ('closed'::character varying)::text])))
);


--
-- Name: program_budgets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.program_budgets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: program_budgets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.program_budgets_id_seq OWNED BY public.program_budgets.id;


--
-- Name: properties; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.properties (
    id bigint NOT NULL,
    property_number character varying(255) NOT NULL,
    erf_number character varying(255),
    title_deed_number character varying(255),
    property_type character varying(255) NOT NULL,
    address text NOT NULL,
    suburb character varying(255) NOT NULL,
    city character varying(255) NOT NULL,
    postal_code character varying(255) NOT NULL,
    size_hectares numeric(10,4),
    market_value numeric(15,2),
    municipal_value numeric(15,2),
    zoning character varying(255),
    ownership_type character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: properties_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.properties_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: properties_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.properties_id_seq OWNED BY public.properties.id;


--
-- Name: property_leases; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.property_leases (
    id bigint NOT NULL,
    lease_number character varying(255) NOT NULL,
    property_id bigint NOT NULL,
    lessee_name character varying(255) NOT NULL,
    lessee_id_number character varying(255) NOT NULL,
    lessee_contact character varying(255) NOT NULL,
    lease_start_date date NOT NULL,
    lease_end_date date NOT NULL,
    monthly_rental numeric(10,2) NOT NULL,
    annual_escalation_percentage numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    deposit_amount numeric(10,2) NOT NULL,
    lease_purpose character varying(255) NOT NULL,
    special_conditions text,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: property_leases_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.property_leases_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: property_leases_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.property_leases_id_seq OWNED BY public.property_leases.id;


--
-- Name: property_rates; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.property_rates (
    id bigint NOT NULL,
    property_id bigint NOT NULL,
    financial_year character varying(255) NOT NULL,
    municipal_value numeric(15,2) NOT NULL,
    rate_cent_amount numeric(8,4) NOT NULL,
    annual_rates numeric(15,2) NOT NULL,
    refuse_charges numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    sewerage_charges numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    other_charges numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    total_amount numeric(15,2) NOT NULL,
    amount_paid numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    outstanding_balance numeric(15,2) NOT NULL,
    status character varying(255) DEFAULT 'current'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: property_rates_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.property_rates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: property_rates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.property_rates_id_seq OWNED BY public.property_rates.id;


--
-- Name: property_valuations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.property_valuations (
    id bigint NOT NULL,
    property_id bigint NOT NULL,
    valuation_date date NOT NULL,
    land_value numeric(15,2) NOT NULL,
    improvement_value numeric(15,2) NOT NULL,
    total_value numeric(15,2) NOT NULL,
    valuation_method character varying(255) NOT NULL,
    valuer_name character varying(255) NOT NULL,
    valuer_registration_number character varying(255),
    notes text,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: property_valuations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.property_valuations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: property_valuations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.property_valuations_id_seq OWNED BY public.property_valuations.id;


--
-- Name: purchase_order_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.purchase_order_items (
    id bigint NOT NULL,
    purchase_order_id bigint NOT NULL,
    item_id bigint NOT NULL,
    quantity numeric(10,2) NOT NULL,
    unit_price numeric(10,2) NOT NULL,
    total_price numeric(15,2) NOT NULL,
    quantity_received numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    received_date date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: purchase_order_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.purchase_order_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: purchase_order_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.purchase_order_items_id_seq OWNED BY public.purchase_order_items.id;


--
-- Name: purchase_orders; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.purchase_orders (
    id bigint NOT NULL,
    po_number character varying(255) NOT NULL,
    supplier_id bigint NOT NULL,
    po_date date NOT NULL,
    delivery_date date,
    description text,
    subtotal numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    tax_rate numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    tax_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    total_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    created_by bigint NOT NULL,
    approved_by bigint,
    approved_date timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT purchase_orders_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'partially_received'::character varying, 'completed'::character varying, 'cancelled'::character varying])::text[])))
);


--
-- Name: purchase_orders_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.purchase_orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: purchase_orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.purchase_orders_id_seq OWNED BY public.purchase_orders.id;


--
-- Name: revenue_collections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.revenue_collections (
    id bigint NOT NULL,
    receipt_number character varying(255) NOT NULL,
    revenue_source character varying(255) NOT NULL,
    source_reference character varying(255),
    customer_id bigint NOT NULL,
    collection_date date NOT NULL,
    amount_collected numeric(15,2) NOT NULL,
    payment_method character varying(255) NOT NULL,
    payment_reference character varying(255),
    collected_by bigint NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: revenue_collections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.revenue_collections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: revenue_collections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.revenue_collections_id_seq OWNED BY public.revenue_collections.id;


--
-- Name: service_request_attachments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.service_request_attachments (
    id bigint NOT NULL,
    service_request_id bigint NOT NULL,
    filename character varying(255) NOT NULL,
    original_name character varying(255) NOT NULL,
    mime_type character varying(255) NOT NULL,
    file_size bigint NOT NULL,
    file_path character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: service_request_attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.service_request_attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: service_request_attachments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.service_request_attachments_id_seq OWNED BY public.service_request_attachments.id;


--
-- Name: service_requests; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.service_requests (
    id bigint NOT NULL,
    request_number character varying(255) NOT NULL,
    customer_id bigint NOT NULL,
    service_type_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    priority character varying(255) DEFAULT 'medium'::character varying NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    assigned_to bigint,
    expected_completion_date date,
    completed_at timestamp(0) without time zone,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: service_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.service_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: service_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.service_requests_id_seq OWNED BY public.service_requests.id;


--
-- Name: service_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.service_types (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    description text,
    fee numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    processing_days integer DEFAULT 1 NOT NULL,
    required_documents json,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: service_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.service_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: service_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.service_types_id_seq OWNED BY public.service_types.id;


--
-- Name: stall_allocations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.stall_allocations (
    id bigint NOT NULL,
    stall_id bigint NOT NULL,
    trader_name character varying(255) NOT NULL,
    trader_id_number character varying(255) NOT NULL,
    trader_contact character varying(255) NOT NULL,
    business_type character varying(255) NOT NULL,
    allocation_date date NOT NULL,
    allocation_type character varying(255) NOT NULL,
    rental_amount numeric(10,2) NOT NULL,
    deposit_paid numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: stall_allocations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.stall_allocations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: stall_allocations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.stall_allocations_id_seq OWNED BY public.stall_allocations.id;


--
-- Name: suppliers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.suppliers (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    contact_person character varying(255),
    email character varying(255),
    phone character varying(255),
    address text,
    city character varying(255),
    state character varying(255),
    postal_code character varying(255),
    country character varying(255) DEFAULT 'Zimbabwe'::character varying NOT NULL,
    tax_number character varying(255),
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT suppliers_status_check CHECK (((status)::text = ANY ((ARRAY['active'::character varying, 'inactive'::character varying])::text[])))
);


--
-- Name: suppliers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.suppliers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: suppliers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.suppliers_id_seq OWNED BY public.suppliers.id;


--
-- Name: survey_projects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.survey_projects (
    id bigint NOT NULL,
    project_number character varying(255) NOT NULL,
    project_name character varying(255) NOT NULL,
    client_name character varying(255) NOT NULL,
    client_contact character varying(255) NOT NULL,
    survey_type character varying(255) NOT NULL,
    project_description text NOT NULL,
    location text NOT NULL,
    start_date date NOT NULL,
    expected_completion_date date NOT NULL,
    actual_completion_date date,
    surveyor_name character varying(255) NOT NULL,
    surveyor_registration character varying(255) NOT NULL,
    project_fee numeric(15,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: survey_projects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.survey_projects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: survey_projects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.survey_projects_id_seq OWNED BY public.survey_projects.id;


--
-- Name: tenants; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tenants (
    id bigint NOT NULL,
    allocation_id bigint NOT NULL,
    tenant_number character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    id_number character varying(255) NOT NULL,
    email character varying(255),
    phone character varying(255) NOT NULL,
    emergency_contact_name character varying(255) NOT NULL,
    emergency_contact_phone character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: tenants_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tenants_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tenants_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tenants_id_seq OWNED BY public.tenants.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    council_id bigint NOT NULL,
    department_id bigint,
    office_id bigint,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    employee_id character varying(255),
    phone character varying(255),
    "position" character varying(255),
    role character varying(255) DEFAULT 'employee'::character varying NOT NULL,
    permissions json,
    hire_date date,
    salary numeric(10,2),
    employment_status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    profile_photo character varying(255),
    active boolean DEFAULT true NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT users_employment_status_check CHECK (((employment_status)::text = ANY (ARRAY[('active'::character varying)::text, ('inactive'::character varying)::text, ('suspended'::character varying)::text, ('terminated'::character varying)::text]))),
    CONSTRAINT users_role_check CHECK (((role)::text = ANY (ARRAY[('admin'::character varying)::text, ('manager'::character varying)::text, ('employee'::character varying)::text, ('super_admin'::character varying)::text])))
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: utilities_connections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.utilities_connections (
    id bigint NOT NULL,
    connection_number character varying(255) NOT NULL,
    property_id bigint NOT NULL,
    customer_id bigint NOT NULL,
    utility_type character varying(255) NOT NULL,
    meter_number character varying(255),
    connection_type character varying(255) NOT NULL,
    connection_date date NOT NULL,
    connection_fee numeric(10,2) NOT NULL,
    deposit_amount numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: utilities_connections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.utilities_connections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: utilities_connections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.utilities_connections_id_seq OWNED BY public.utilities_connections.id;


--
-- Name: voucher_lines; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.voucher_lines (
    id bigint NOT NULL,
    voucher_id bigint NOT NULL,
    account_code character varying(255) NOT NULL,
    description text NOT NULL,
    debit_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    credit_amount numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: voucher_lines_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.voucher_lines_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: voucher_lines_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.voucher_lines_id_seq OWNED BY public.voucher_lines.id;


--
-- Name: vouchers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.vouchers (
    id bigint NOT NULL,
    voucher_number character varying(255) NOT NULL,
    voucher_type character varying(255) NOT NULL,
    voucher_date date NOT NULL,
    description text NOT NULL,
    total_amount numeric(15,2) NOT NULL,
    currency_code character varying(3) NOT NULL,
    payee_name character varying(255),
    payment_method character varying(255),
    reference_number character varying(255),
    prepared_by bigint NOT NULL,
    approved_by bigint,
    approved_at timestamp(0) without time zone,
    status character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT vouchers_payment_method_check CHECK (((payment_method)::text = ANY (ARRAY[('cash'::character varying)::text, ('cheque'::character varying)::text, ('electronic'::character varying)::text, ('mobile'::character varying)::text]))),
    CONSTRAINT vouchers_status_check CHECK (((status)::text = ANY (ARRAY[('draft'::character varying)::text, ('pending_approval'::character varying)::text, ('approved'::character varying)::text, ('paid'::character varying)::text, ('cancelled'::character varying)::text]))),
    CONSTRAINT vouchers_voucher_type_check CHECK (((voucher_type)::text = ANY (ARRAY[('payment'::character varying)::text, ('receipt'::character varying)::text, ('journal'::character varying)::text])))
);


--
-- Name: vouchers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.vouchers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: vouchers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.vouchers_id_seq OWNED BY public.vouchers.id;


--
-- Name: waste_collection_routes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.waste_collection_routes (
    id bigint NOT NULL,
    route_name character varying(255) NOT NULL,
    route_code character varying(255) NOT NULL,
    route_description text NOT NULL,
    collection_days json NOT NULL,
    start_time time(0) without time zone NOT NULL,
    estimated_completion_time time(0) without time zone NOT NULL,
    estimated_households integer NOT NULL,
    assigned_vehicle_id bigint,
    assigned_driver character varying(255),
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: waste_collection_routes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.waste_collection_routes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: waste_collection_routes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.waste_collection_routes_id_seq OWNED BY public.waste_collection_routes.id;


--
-- Name: water_bills; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.water_bills (
    id bigint NOT NULL,
    bill_number character varying(255) NOT NULL,
    connection_id bigint NOT NULL,
    bill_date date NOT NULL,
    due_date date NOT NULL,
    billing_period character varying(255) NOT NULL,
    consumption numeric(10,2) NOT NULL,
    basic_charge numeric(10,2) NOT NULL,
    consumption_charge numeric(10,2) NOT NULL,
    vat_amount numeric(10,2) NOT NULL,
    total_amount numeric(10,2) NOT NULL,
    amount_paid numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    balance numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'unpaid'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: water_bills_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.water_bills_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: water_bills_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.water_bills_id_seq OWNED BY public.water_bills.id;


--
-- Name: water_connections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.water_connections (
    id bigint NOT NULL,
    connection_number character varying(255) NOT NULL,
    property_id bigint NOT NULL,
    customer_id bigint NOT NULL,
    meter_number character varying(255) NOT NULL,
    meter_size character varying(255) NOT NULL,
    connection_date date NOT NULL,
    connection_type character varying(255) NOT NULL,
    deposit_paid numeric(10,2) NOT NULL,
    connection_fee numeric(10,2) NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: water_connections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.water_connections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: water_connections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.water_connections_id_seq OWNED BY public.water_connections.id;


--
-- Name: water_meter_readings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.water_meter_readings (
    id bigint NOT NULL,
    connection_id bigint NOT NULL,
    reading_date date NOT NULL,
    previous_reading numeric(10,2) NOT NULL,
    current_reading numeric(10,2) NOT NULL,
    consumption numeric(10,2) NOT NULL,
    reader_name character varying(255) NOT NULL,
    notes text,
    estimated boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: water_meter_readings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.water_meter_readings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: water_meter_readings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.water_meter_readings_id_seq OWNED BY public.water_meter_readings.id;


--
-- Name: zimbabwe_chart_of_accounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.zimbabwe_chart_of_accounts (
    id bigint NOT NULL,
    account_code character varying(20) NOT NULL,
    account_name character varying(255) NOT NULL,
    account_type character varying(255) NOT NULL,
    account_category character varying(50) NOT NULL,
    account_subcategory character varying(50),
    account_level integer NOT NULL,
    parent_account_code character varying(20),
    is_control_account boolean DEFAULT false NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    government_classification character varying(50),
    ipsas_classification character varying(50),
    description text,
    opening_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    current_balance numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT zimbabwe_chart_of_accounts_account_type_check CHECK (((account_type)::text = ANY (ARRAY[('asset'::character varying)::text, ('liability'::character varying)::text, ('equity'::character varying)::text, ('revenue'::character varying)::text, ('expense'::character varying)::text])))
);


--
-- Name: zimbabwe_chart_of_accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.zimbabwe_chart_of_accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: zimbabwe_chart_of_accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.zimbabwe_chart_of_accounts_id_seq OWNED BY public.zimbabwe_chart_of_accounts.id;


--
-- Name: ap_bills id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills ALTER COLUMN id SET DEFAULT nextval('public.ap_bills_id_seq'::regclass);


--
-- Name: ap_vendors id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_vendors ALTER COLUMN id SET DEFAULT nextval('public.ap_vendors_id_seq'::regclass);


--
-- Name: ar_invoices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_invoices ALTER COLUMN id SET DEFAULT nextval('public.ar_invoices_id_seq'::regclass);


--
-- Name: ar_receipts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts ALTER COLUMN id SET DEFAULT nextval('public.ar_receipts_id_seq'::regclass);


--
-- Name: asset_categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_categories ALTER COLUMN id SET DEFAULT nextval('public.asset_categories_id_seq'::regclass);


--
-- Name: asset_depreciation_history id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_depreciation_history ALTER COLUMN id SET DEFAULT nextval('public.asset_depreciation_history_id_seq'::regclass);


--
-- Name: asset_locations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_locations ALTER COLUMN id SET DEFAULT nextval('public.asset_locations_id_seq'::regclass);


--
-- Name: bank_accounts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_accounts ALTER COLUMN id SET DEFAULT nextval('public.bank_accounts_id_seq'::regclass);


--
-- Name: bank_reconciliations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations ALTER COLUMN id SET DEFAULT nextval('public.bank_reconciliations_id_seq'::regclass);


--
-- Name: bank_statements id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_statements ALTER COLUMN id SET DEFAULT nextval('public.bank_statements_id_seq'::regclass);


--
-- Name: bill_line_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_line_items ALTER COLUMN id SET DEFAULT nextval('public.bill_line_items_id_seq'::regclass);


--
-- Name: bill_payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_payments ALTER COLUMN id SET DEFAULT nextval('public.bill_payments_id_seq'::regclass);


--
-- Name: bill_reminders id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_reminders ALTER COLUMN id SET DEFAULT nextval('public.bill_reminders_id_seq'::regclass);


--
-- Name: budgets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets ALTER COLUMN id SET DEFAULT nextval('public.budgets_id_seq'::regclass);


--
-- Name: building_inspections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.building_inspections ALTER COLUMN id SET DEFAULT nextval('public.building_inspections_id_seq'::regclass);


--
-- Name: burial_records id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.burial_records ALTER COLUMN id SET DEFAULT nextval('public.burial_records_id_seq'::regclass);


--
-- Name: cash_transactions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions ALTER COLUMN id SET DEFAULT nextval('public.cash_transactions_id_seq'::regclass);


--
-- Name: cashbook_entries id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cashbook_entries ALTER COLUMN id SET DEFAULT nextval('public.cashbook_entries_id_seq'::regclass);


--
-- Name: cemeteries id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemeteries ALTER COLUMN id SET DEFAULT nextval('public.cemeteries_id_seq'::regclass);


--
-- Name: cemetery_plots id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemetery_plots ALTER COLUMN id SET DEFAULT nextval('public.cemetery_plots_id_seq'::regclass);


--
-- Name: committee_committees id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.committee_committees ALTER COLUMN id SET DEFAULT nextval('public.committee_committees_id_seq'::regclass);


--
-- Name: committee_meetings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.committee_meetings ALTER COLUMN id SET DEFAULT nextval('public.committee_meetings_id_seq'::regclass);


--
-- Name: communications id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.communications ALTER COLUMN id SET DEFAULT nextval('public.communications_id_seq'::regclass);


--
-- Name: cost_centers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cost_centers ALTER COLUMN id SET DEFAULT nextval('public.cost_centers_id_seq'::regclass);


--
-- Name: councils id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.councils ALTER COLUMN id SET DEFAULT nextval('public.councils_id_seq'::regclass);


--
-- Name: currencies id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.currencies ALTER COLUMN id SET DEFAULT nextval('public.currencies_id_seq'::regclass);


--
-- Name: customer_accounts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_accounts ALTER COLUMN id SET DEFAULT nextval('public.customer_accounts_id_seq'::regclass);


--
-- Name: customers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers ALTER COLUMN id SET DEFAULT nextval('public.customers_id_seq'::regclass);


--
-- Name: debtor_transactions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtor_transactions ALTER COLUMN id SET DEFAULT nextval('public.debtor_transactions_id_seq'::regclass);


--
-- Name: debtors id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtors ALTER COLUMN id SET DEFAULT nextval('public.debtors_id_seq'::regclass);


--
-- Name: departments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments ALTER COLUMN id SET DEFAULT nextval('public.departments_id_seq'::regclass);


--
-- Name: engineering_projects id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.engineering_projects ALTER COLUMN id SET DEFAULT nextval('public.engineering_projects_id_seq'::regclass);


--
-- Name: event_permits id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.event_permits ALTER COLUMN id SET DEFAULT nextval('public.event_permits_id_seq'::regclass);


--
-- Name: exchange_rate_histories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.exchange_rate_histories ALTER COLUMN id SET DEFAULT nextval('public.exchange_rate_histories_id_seq'::regclass);


--
-- Name: facilities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facilities ALTER COLUMN id SET DEFAULT nextval('public.facilities_id_seq'::regclass);


--
-- Name: facility_bookings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facility_bookings ALTER COLUMN id SET DEFAULT nextval('public.facility_bookings_id_seq'::regclass);


--
-- Name: fdms_receipts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts ALTER COLUMN id SET DEFAULT nextval('public.fdms_receipts_id_seq'::regclass);


--
-- Name: fdms_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_settings ALTER COLUMN id SET DEFAULT nextval('public.fdms_settings_id_seq'::regclass);


--
-- Name: finance_budgets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_budgets ALTER COLUMN id SET DEFAULT nextval('public.finance_budgets_id_seq'::regclass);


--
-- Name: finance_chart_of_accounts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_chart_of_accounts ALTER COLUMN id SET DEFAULT nextval('public.finance_chart_of_accounts_id_seq'::regclass);


--
-- Name: finance_general_journal_headers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers ALTER COLUMN id SET DEFAULT nextval('public.finance_general_journal_headers_id_seq'::regclass);


--
-- Name: finance_general_journal_lines id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_lines ALTER COLUMN id SET DEFAULT nextval('public.finance_general_journal_lines_id_seq'::regclass);


--
-- Name: finance_general_ledger id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_ledger ALTER COLUMN id SET DEFAULT nextval('public.finance_general_ledger_id_seq'::regclass);


--
-- Name: finance_invoice_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoice_items ALTER COLUMN id SET DEFAULT nextval('public.finance_invoice_items_id_seq'::regclass);


--
-- Name: finance_invoices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoices ALTER COLUMN id SET DEFAULT nextval('public.finance_invoices_id_seq'::regclass);


--
-- Name: finance_payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments ALTER COLUMN id SET DEFAULT nextval('public.finance_payments_id_seq'::regclass);


--
-- Name: financial_reports id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.financial_reports ALTER COLUMN id SET DEFAULT nextval('public.financial_reports_id_seq'::regclass);


--
-- Name: fiscal_devices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fiscal_devices ALTER COLUMN id SET DEFAULT nextval('public.fiscal_devices_id_seq'::regclass);


--
-- Name: fixed_assets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fixed_assets ALTER COLUMN id SET DEFAULT nextval('public.fixed_assets_id_seq'::regclass);


--
-- Name: fleet_vehicles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fleet_vehicles ALTER COLUMN id SET DEFAULT nextval('public.fleet_vehicles_id_seq'::regclass);


--
-- Name: gate_takings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gate_takings ALTER COLUMN id SET DEFAULT nextval('public.gate_takings_id_seq'::regclass);


--
-- Name: general_ledger id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.general_ledger ALTER COLUMN id SET DEFAULT nextval('public.general_ledger_id_seq'::regclass);


--
-- Name: health_facilities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_facilities ALTER COLUMN id SET DEFAULT nextval('public.health_facilities_id_seq'::regclass);


--
-- Name: health_inspections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_inspections ALTER COLUMN id SET DEFAULT nextval('public.health_inspections_id_seq'::regclass);


--
-- Name: housing_allocations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_allocations ALTER COLUMN id SET DEFAULT nextval('public.housing_allocations_id_seq'::regclass);


--
-- Name: housing_applications id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_applications ALTER COLUMN id SET DEFAULT nextval('public.housing_applications_id_seq'::regclass);


--
-- Name: housing_properties id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_properties ALTER COLUMN id SET DEFAULT nextval('public.housing_properties_id_seq'::regclass);


--
-- Name: housing_waiting_list id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_waiting_list ALTER COLUMN id SET DEFAULT nextval('public.housing_waiting_list_id_seq'::regclass);


--
-- Name: hr_attendance id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_attendance ALTER COLUMN id SET DEFAULT nextval('public.hr_attendance_id_seq'::regclass);


--
-- Name: hr_employees id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees ALTER COLUMN id SET DEFAULT nextval('public.hr_employees_id_seq'::regclass);


--
-- Name: hr_payroll id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_payroll ALTER COLUMN id SET DEFAULT nextval('public.hr_payroll_id_seq'::regclass);


--
-- Name: infrastructure_assets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.infrastructure_assets ALTER COLUMN id SET DEFAULT nextval('public.infrastructure_assets_id_seq'::regclass);


--
-- Name: inventory_categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_categories ALTER COLUMN id SET DEFAULT nextval('public.inventory_categories_id_seq'::regclass);


--
-- Name: inventory_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items ALTER COLUMN id SET DEFAULT nextval('public.inventory_items_id_seq'::regclass);


--
-- Name: inventory_transactions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_transactions ALTER COLUMN id SET DEFAULT nextval('public.inventory_transactions_id_seq'::regclass);


--
-- Name: licensing_business_licenses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.licensing_business_licenses ALTER COLUMN id SET DEFAULT nextval('public.licensing_business_licenses_id_seq'::regclass);


--
-- Name: market_stalls id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.market_stalls ALTER COLUMN id SET DEFAULT nextval('public.market_stalls_id_seq'::regclass);


--
-- Name: markets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.markets ALTER COLUMN id SET DEFAULT nextval('public.markets_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: municipal_bills id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_bills ALTER COLUMN id SET DEFAULT nextval('public.municipal_bills_id_seq'::regclass);


--
-- Name: municipal_service_categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_service_categories ALTER COLUMN id SET DEFAULT nextval('public.municipal_service_categories_id_seq'::regclass);


--
-- Name: municipal_services id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_services ALTER COLUMN id SET DEFAULT nextval('public.municipal_services_id_seq'::regclass);


--
-- Name: offices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offices ALTER COLUMN id SET DEFAULT nextval('public.offices_id_seq'::regclass);


--
-- Name: parking_violations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_violations ALTER COLUMN id SET DEFAULT nextval('public.parking_violations_id_seq'::regclass);


--
-- Name: parking_zones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_zones ALTER COLUMN id SET DEFAULT nextval('public.parking_zones_id_seq'::regclass);


--
-- Name: payment_methods id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_methods ALTER COLUMN id SET DEFAULT nextval('public.payment_methods_id_seq'::regclass);


--
-- Name: payment_vouchers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers ALTER COLUMN id SET DEFAULT nextval('public.payment_vouchers_id_seq'::regclass);


--
-- Name: planning_applications id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.planning_applications ALTER COLUMN id SET DEFAULT nextval('public.planning_applications_id_seq'::regclass);


--
-- Name: pos_terminals id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pos_terminals ALTER COLUMN id SET DEFAULT nextval('public.pos_terminals_id_seq'::regclass);


--
-- Name: program_budgets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.program_budgets ALTER COLUMN id SET DEFAULT nextval('public.program_budgets_id_seq'::regclass);


--
-- Name: properties id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.properties ALTER COLUMN id SET DEFAULT nextval('public.properties_id_seq'::regclass);


--
-- Name: property_leases id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_leases ALTER COLUMN id SET DEFAULT nextval('public.property_leases_id_seq'::regclass);


--
-- Name: property_rates id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_rates ALTER COLUMN id SET DEFAULT nextval('public.property_rates_id_seq'::regclass);


--
-- Name: property_valuations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_valuations ALTER COLUMN id SET DEFAULT nextval('public.property_valuations_id_seq'::regclass);


--
-- Name: purchase_order_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_order_items ALTER COLUMN id SET DEFAULT nextval('public.purchase_order_items_id_seq'::regclass);


--
-- Name: purchase_orders id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders ALTER COLUMN id SET DEFAULT nextval('public.purchase_orders_id_seq'::regclass);


--
-- Name: revenue_collections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.revenue_collections ALTER COLUMN id SET DEFAULT nextval('public.revenue_collections_id_seq'::regclass);


--
-- Name: service_request_attachments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_request_attachments ALTER COLUMN id SET DEFAULT nextval('public.service_request_attachments_id_seq'::regclass);


--
-- Name: service_requests id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests ALTER COLUMN id SET DEFAULT nextval('public.service_requests_id_seq'::regclass);


--
-- Name: service_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_types ALTER COLUMN id SET DEFAULT nextval('public.service_types_id_seq'::regclass);


--
-- Name: stall_allocations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stall_allocations ALTER COLUMN id SET DEFAULT nextval('public.stall_allocations_id_seq'::regclass);


--
-- Name: suppliers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.suppliers ALTER COLUMN id SET DEFAULT nextval('public.suppliers_id_seq'::regclass);


--
-- Name: survey_projects id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.survey_projects ALTER COLUMN id SET DEFAULT nextval('public.survey_projects_id_seq'::regclass);


--
-- Name: tenants id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tenants ALTER COLUMN id SET DEFAULT nextval('public.tenants_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: utilities_connections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilities_connections ALTER COLUMN id SET DEFAULT nextval('public.utilities_connections_id_seq'::regclass);


--
-- Name: voucher_lines id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.voucher_lines ALTER COLUMN id SET DEFAULT nextval('public.voucher_lines_id_seq'::regclass);


--
-- Name: vouchers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers ALTER COLUMN id SET DEFAULT nextval('public.vouchers_id_seq'::regclass);


--
-- Name: waste_collection_routes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.waste_collection_routes ALTER COLUMN id SET DEFAULT nextval('public.waste_collection_routes_id_seq'::regclass);


--
-- Name: water_bills id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_bills ALTER COLUMN id SET DEFAULT nextval('public.water_bills_id_seq'::regclass);


--
-- Name: water_connections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections ALTER COLUMN id SET DEFAULT nextval('public.water_connections_id_seq'::regclass);


--
-- Name: water_meter_readings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_meter_readings ALTER COLUMN id SET DEFAULT nextval('public.water_meter_readings_id_seq'::regclass);


--
-- Name: zimbabwe_chart_of_accounts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.zimbabwe_chart_of_accounts ALTER COLUMN id SET DEFAULT nextval('public.zimbabwe_chart_of_accounts_id_seq'::regclass);


--
-- Name: ap_bills ap_bills_bill_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills
    ADD CONSTRAINT ap_bills_bill_number_unique UNIQUE (bill_number);


--
-- Name: ap_bills ap_bills_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills
    ADD CONSTRAINT ap_bills_pkey PRIMARY KEY (id);


--
-- Name: ap_vendors ap_vendors_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_vendors
    ADD CONSTRAINT ap_vendors_pkey PRIMARY KEY (id);


--
-- Name: ap_vendors ap_vendors_vendor_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_vendors
    ADD CONSTRAINT ap_vendors_vendor_number_unique UNIQUE (vendor_number);


--
-- Name: ar_invoices ar_invoices_invoice_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_invoices
    ADD CONSTRAINT ar_invoices_invoice_number_unique UNIQUE (invoice_number);


--
-- Name: ar_invoices ar_invoices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_invoices
    ADD CONSTRAINT ar_invoices_pkey PRIMARY KEY (id);


--
-- Name: ar_receipts ar_receipts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_pkey PRIMARY KEY (id);


--
-- Name: ar_receipts ar_receipts_receipt_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_receipt_number_unique UNIQUE (receipt_number);


--
-- Name: asset_categories asset_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_categories
    ADD CONSTRAINT asset_categories_pkey PRIMARY KEY (id);


--
-- Name: asset_depreciation_history asset_depreciation_history_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_depreciation_history
    ADD CONSTRAINT asset_depreciation_history_pkey PRIMARY KEY (id);


--
-- Name: asset_locations asset_locations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_locations
    ADD CONSTRAINT asset_locations_pkey PRIMARY KEY (id);


--
-- Name: bank_accounts bank_accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_accounts
    ADD CONSTRAINT bank_accounts_pkey PRIMARY KEY (id);


--
-- Name: bank_reconciliations bank_reconciliations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_pkey PRIMARY KEY (id);


--
-- Name: bank_reconciliations bank_reconciliations_reconciliation_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_reconciliation_number_unique UNIQUE (reconciliation_number);


--
-- Name: bank_statements bank_statements_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_statements
    ADD CONSTRAINT bank_statements_pkey PRIMARY KEY (id);


--
-- Name: bank_statements bank_statements_statement_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_statements
    ADD CONSTRAINT bank_statements_statement_number_unique UNIQUE (statement_number);


--
-- Name: bill_line_items bill_line_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_line_items
    ADD CONSTRAINT bill_line_items_pkey PRIMARY KEY (id);


--
-- Name: bill_payments bill_payments_payment_reference_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_payments
    ADD CONSTRAINT bill_payments_payment_reference_unique UNIQUE (payment_reference);


--
-- Name: bill_payments bill_payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_payments
    ADD CONSTRAINT bill_payments_pkey PRIMARY KEY (id);


--
-- Name: bill_reminders bill_reminders_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_reminders
    ADD CONSTRAINT bill_reminders_pkey PRIMARY KEY (id);


--
-- Name: budgets budgets_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT budgets_code_unique UNIQUE (code);


--
-- Name: budgets budgets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT budgets_pkey PRIMARY KEY (id);


--
-- Name: building_inspections building_inspections_inspection_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.building_inspections
    ADD CONSTRAINT building_inspections_inspection_number_unique UNIQUE (inspection_number);


--
-- Name: building_inspections building_inspections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.building_inspections
    ADD CONSTRAINT building_inspections_pkey PRIMARY KEY (id);


--
-- Name: burial_records burial_records_burial_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.burial_records
    ADD CONSTRAINT burial_records_burial_number_unique UNIQUE (burial_number);


--
-- Name: burial_records burial_records_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.burial_records
    ADD CONSTRAINT burial_records_pkey PRIMARY KEY (id);


--
-- Name: cash_transactions cash_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions
    ADD CONSTRAINT cash_transactions_pkey PRIMARY KEY (id);


--
-- Name: cashbook_entries cashbook_entries_entry_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cashbook_entries
    ADD CONSTRAINT cashbook_entries_entry_number_unique UNIQUE (entry_number);


--
-- Name: cashbook_entries cashbook_entries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cashbook_entries
    ADD CONSTRAINT cashbook_entries_pkey PRIMARY KEY (id);


--
-- Name: cemeteries cemeteries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemeteries
    ADD CONSTRAINT cemeteries_pkey PRIMARY KEY (id);


--
-- Name: cemetery_plots cemetery_plots_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemetery_plots
    ADD CONSTRAINT cemetery_plots_pkey PRIMARY KEY (id);


--
-- Name: cemetery_plots cemetery_plots_plot_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemetery_plots
    ADD CONSTRAINT cemetery_plots_plot_number_unique UNIQUE (plot_number);


--
-- Name: committee_committees committee_committees_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.committee_committees
    ADD CONSTRAINT committee_committees_pkey PRIMARY KEY (id);


--
-- Name: committee_meetings committee_meetings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.committee_meetings
    ADD CONSTRAINT committee_meetings_pkey PRIMARY KEY (id);


--
-- Name: communications communications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.communications
    ADD CONSTRAINT communications_pkey PRIMARY KEY (id);


--
-- Name: cost_centers cost_centers_cost_center_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cost_centers
    ADD CONSTRAINT cost_centers_cost_center_code_unique UNIQUE (cost_center_code);


--
-- Name: cost_centers cost_centers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cost_centers
    ADD CONSTRAINT cost_centers_pkey PRIMARY KEY (id);


--
-- Name: councils councils_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.councils
    ADD CONSTRAINT councils_code_unique UNIQUE (code);


--
-- Name: councils councils_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.councils
    ADD CONSTRAINT councils_pkey PRIMARY KEY (id);


--
-- Name: currencies currencies_currency_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.currencies
    ADD CONSTRAINT currencies_currency_code_unique UNIQUE (currency_code);


--
-- Name: currencies currencies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.currencies
    ADD CONSTRAINT currencies_pkey PRIMARY KEY (id);


--
-- Name: customer_accounts customer_accounts_account_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_accounts
    ADD CONSTRAINT customer_accounts_account_number_unique UNIQUE (account_number);


--
-- Name: customer_accounts customer_accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_accounts
    ADD CONSTRAINT customer_accounts_pkey PRIMARY KEY (id);


--
-- Name: customers customers_customer_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers
    ADD CONSTRAINT customers_customer_number_unique UNIQUE (customer_number);


--
-- Name: customers customers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers
    ADD CONSTRAINT customers_pkey PRIMARY KEY (id);


--
-- Name: debtor_transactions debtor_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtor_transactions
    ADD CONSTRAINT debtor_transactions_pkey PRIMARY KEY (id);


--
-- Name: debtors debtors_debtor_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtors
    ADD CONSTRAINT debtors_debtor_number_unique UNIQUE (debtor_number);


--
-- Name: debtors debtors_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtors
    ADD CONSTRAINT debtors_pkey PRIMARY KEY (id);


--
-- Name: departments departments_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_code_unique UNIQUE (code);


--
-- Name: departments departments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_pkey PRIMARY KEY (id);


--
-- Name: engineering_projects engineering_projects_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.engineering_projects
    ADD CONSTRAINT engineering_projects_pkey PRIMARY KEY (id);


--
-- Name: engineering_projects engineering_projects_project_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.engineering_projects
    ADD CONSTRAINT engineering_projects_project_number_unique UNIQUE (project_number);


--
-- Name: event_permits event_permits_permit_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.event_permits
    ADD CONSTRAINT event_permits_permit_number_unique UNIQUE (permit_number);


--
-- Name: event_permits event_permits_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.event_permits
    ADD CONSTRAINT event_permits_pkey PRIMARY KEY (id);


--
-- Name: exchange_rate_histories exchange_rate_histories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.exchange_rate_histories
    ADD CONSTRAINT exchange_rate_histories_pkey PRIMARY KEY (id);


--
-- Name: facilities facilities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facilities
    ADD CONSTRAINT facilities_pkey PRIMARY KEY (id);


--
-- Name: facility_bookings facility_bookings_booking_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facility_bookings
    ADD CONSTRAINT facility_bookings_booking_number_unique UNIQUE (booking_number);


--
-- Name: facility_bookings facility_bookings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facility_bookings
    ADD CONSTRAINT facility_bookings_pkey PRIMARY KEY (id);


--
-- Name: fdms_receipts fdms_receipts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_pkey PRIMARY KEY (id);


--
-- Name: fdms_receipts fdms_receipts_receipt_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_receipt_number_unique UNIQUE (receipt_number);


--
-- Name: fdms_settings fdms_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_settings
    ADD CONSTRAINT fdms_settings_pkey PRIMARY KEY (id);


--
-- Name: finance_budgets finance_budgets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_budgets
    ADD CONSTRAINT finance_budgets_pkey PRIMARY KEY (id);


--
-- Name: finance_chart_of_accounts finance_chart_of_accounts_account_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_chart_of_accounts
    ADD CONSTRAINT finance_chart_of_accounts_account_code_unique UNIQUE (account_code);


--
-- Name: finance_chart_of_accounts finance_chart_of_accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_chart_of_accounts
    ADD CONSTRAINT finance_chart_of_accounts_pkey PRIMARY KEY (id);


--
-- Name: finance_general_journal_headers finance_general_journal_headers_journal_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers
    ADD CONSTRAINT finance_general_journal_headers_journal_number_unique UNIQUE (journal_number);


--
-- Name: finance_general_journal_headers finance_general_journal_headers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers
    ADD CONSTRAINT finance_general_journal_headers_pkey PRIMARY KEY (id);


--
-- Name: finance_general_journal_lines finance_general_journal_lines_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_lines
    ADD CONSTRAINT finance_general_journal_lines_pkey PRIMARY KEY (id);


--
-- Name: finance_general_ledger finance_general_ledger_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_ledger
    ADD CONSTRAINT finance_general_ledger_pkey PRIMARY KEY (id);


--
-- Name: finance_general_ledger finance_general_ledger_transaction_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_ledger
    ADD CONSTRAINT finance_general_ledger_transaction_number_unique UNIQUE (transaction_number);


--
-- Name: finance_invoice_items finance_invoice_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoice_items
    ADD CONSTRAINT finance_invoice_items_pkey PRIMARY KEY (id);


--
-- Name: finance_invoices finance_invoices_invoice_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoices
    ADD CONSTRAINT finance_invoices_invoice_number_unique UNIQUE (invoice_number);


--
-- Name: finance_invoices finance_invoices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoices
    ADD CONSTRAINT finance_invoices_pkey PRIMARY KEY (id);


--
-- Name: finance_payments finance_payments_payment_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments
    ADD CONSTRAINT finance_payments_payment_number_unique UNIQUE (payment_number);


--
-- Name: finance_payments finance_payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments
    ADD CONSTRAINT finance_payments_pkey PRIMARY KEY (id);


--
-- Name: financial_reports financial_reports_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.financial_reports
    ADD CONSTRAINT financial_reports_pkey PRIMARY KEY (id);


--
-- Name: fiscal_devices fiscal_devices_device_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fiscal_devices
    ADD CONSTRAINT fiscal_devices_device_id_unique UNIQUE (device_id);


--
-- Name: fiscal_devices fiscal_devices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fiscal_devices
    ADD CONSTRAINT fiscal_devices_pkey PRIMARY KEY (id);


--
-- Name: fixed_assets fixed_assets_asset_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fixed_assets
    ADD CONSTRAINT fixed_assets_asset_number_unique UNIQUE (asset_number);


--
-- Name: fixed_assets fixed_assets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fixed_assets
    ADD CONSTRAINT fixed_assets_pkey PRIMARY KEY (id);


--
-- Name: fleet_vehicles fleet_vehicles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fleet_vehicles
    ADD CONSTRAINT fleet_vehicles_pkey PRIMARY KEY (id);


--
-- Name: fleet_vehicles fleet_vehicles_registration_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fleet_vehicles
    ADD CONSTRAINT fleet_vehicles_registration_number_unique UNIQUE (registration_number);


--
-- Name: fleet_vehicles fleet_vehicles_vehicle_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fleet_vehicles
    ADD CONSTRAINT fleet_vehicles_vehicle_number_unique UNIQUE (vehicle_number);


--
-- Name: gate_takings gate_takings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gate_takings
    ADD CONSTRAINT gate_takings_pkey PRIMARY KEY (id);


--
-- Name: general_ledger general_ledger_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.general_ledger
    ADD CONSTRAINT general_ledger_pkey PRIMARY KEY (id);


--
-- Name: health_facilities health_facilities_license_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_facilities
    ADD CONSTRAINT health_facilities_license_number_unique UNIQUE (license_number);


--
-- Name: health_facilities health_facilities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_facilities
    ADD CONSTRAINT health_facilities_pkey PRIMARY KEY (id);


--
-- Name: health_inspections health_inspections_inspection_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_inspections
    ADD CONSTRAINT health_inspections_inspection_number_unique UNIQUE (inspection_number);


--
-- Name: health_inspections health_inspections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_inspections
    ADD CONSTRAINT health_inspections_pkey PRIMARY KEY (id);


--
-- Name: housing_allocations housing_allocations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_allocations
    ADD CONSTRAINT housing_allocations_pkey PRIMARY KEY (id);


--
-- Name: housing_applications housing_applications_application_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_applications
    ADD CONSTRAINT housing_applications_application_number_unique UNIQUE (application_number);


--
-- Name: housing_applications housing_applications_id_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_applications
    ADD CONSTRAINT housing_applications_id_number_unique UNIQUE (id_number);


--
-- Name: housing_applications housing_applications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_applications
    ADD CONSTRAINT housing_applications_pkey PRIMARY KEY (id);


--
-- Name: housing_properties housing_properties_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_properties
    ADD CONSTRAINT housing_properties_pkey PRIMARY KEY (id);


--
-- Name: housing_properties housing_properties_property_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_properties
    ADD CONSTRAINT housing_properties_property_number_unique UNIQUE (property_number);


--
-- Name: housing_waiting_list housing_waiting_list_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_waiting_list
    ADD CONSTRAINT housing_waiting_list_pkey PRIMARY KEY (id);


--
-- Name: hr_attendance hr_attendance_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_attendance
    ADD CONSTRAINT hr_attendance_pkey PRIMARY KEY (id);


--
-- Name: hr_employees hr_employees_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees
    ADD CONSTRAINT hr_employees_email_unique UNIQUE (email);


--
-- Name: hr_employees hr_employees_employee_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees
    ADD CONSTRAINT hr_employees_employee_number_unique UNIQUE (employee_number);


--
-- Name: hr_employees hr_employees_id_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees
    ADD CONSTRAINT hr_employees_id_number_unique UNIQUE (id_number);


--
-- Name: hr_employees hr_employees_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees
    ADD CONSTRAINT hr_employees_pkey PRIMARY KEY (id);


--
-- Name: hr_payroll hr_payroll_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_payroll
    ADD CONSTRAINT hr_payroll_pkey PRIMARY KEY (id);


--
-- Name: infrastructure_assets infrastructure_assets_asset_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.infrastructure_assets
    ADD CONSTRAINT infrastructure_assets_asset_number_unique UNIQUE (asset_number);


--
-- Name: infrastructure_assets infrastructure_assets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.infrastructure_assets
    ADD CONSTRAINT infrastructure_assets_pkey PRIMARY KEY (id);


--
-- Name: inventory_categories inventory_categories_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_categories
    ADD CONSTRAINT inventory_categories_code_unique UNIQUE (code);


--
-- Name: inventory_categories inventory_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_categories
    ADD CONSTRAINT inventory_categories_pkey PRIMARY KEY (id);


--
-- Name: inventory_items inventory_items_item_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_item_code_unique UNIQUE (item_code);


--
-- Name: inventory_items inventory_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_pkey PRIMARY KEY (id);


--
-- Name: inventory_transactions inventory_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_transactions
    ADD CONSTRAINT inventory_transactions_pkey PRIMARY KEY (id);


--
-- Name: inventory_transactions inventory_transactions_transaction_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_transactions
    ADD CONSTRAINT inventory_transactions_transaction_number_unique UNIQUE (transaction_number);


--
-- Name: licensing_business_licenses licensing_business_licenses_license_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.licensing_business_licenses
    ADD CONSTRAINT licensing_business_licenses_license_number_unique UNIQUE (license_number);


--
-- Name: licensing_business_licenses licensing_business_licenses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.licensing_business_licenses
    ADD CONSTRAINT licensing_business_licenses_pkey PRIMARY KEY (id);


--
-- Name: market_stalls market_stalls_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.market_stalls
    ADD CONSTRAINT market_stalls_pkey PRIMARY KEY (id);


--
-- Name: markets markets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.markets
    ADD CONSTRAINT markets_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: municipal_bills municipal_bills_bill_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_bills
    ADD CONSTRAINT municipal_bills_bill_number_unique UNIQUE (bill_number);


--
-- Name: municipal_bills municipal_bills_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_bills
    ADD CONSTRAINT municipal_bills_pkey PRIMARY KEY (id);


--
-- Name: municipal_service_categories municipal_service_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_service_categories
    ADD CONSTRAINT municipal_service_categories_pkey PRIMARY KEY (id);


--
-- Name: municipal_services municipal_services_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_services
    ADD CONSTRAINT municipal_services_code_unique UNIQUE (code);


--
-- Name: municipal_services municipal_services_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_services
    ADD CONSTRAINT municipal_services_pkey PRIMARY KEY (id);


--
-- Name: offices offices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offices
    ADD CONSTRAINT offices_pkey PRIMARY KEY (id);


--
-- Name: parking_violations parking_violations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_violations
    ADD CONSTRAINT parking_violations_pkey PRIMARY KEY (id);


--
-- Name: parking_violations parking_violations_violation_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_violations
    ADD CONSTRAINT parking_violations_violation_number_unique UNIQUE (violation_number);


--
-- Name: parking_zones parking_zones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_zones
    ADD CONSTRAINT parking_zones_pkey PRIMARY KEY (id);


--
-- Name: parking_zones parking_zones_zone_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_zones
    ADD CONSTRAINT parking_zones_zone_code_unique UNIQUE (zone_code);


--
-- Name: payment_methods payment_methods_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_methods
    ADD CONSTRAINT payment_methods_code_unique UNIQUE (code);


--
-- Name: payment_methods payment_methods_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_methods
    ADD CONSTRAINT payment_methods_pkey PRIMARY KEY (id);


--
-- Name: payment_vouchers payment_vouchers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_pkey PRIMARY KEY (id);


--
-- Name: payment_vouchers payment_vouchers_voucher_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_voucher_number_unique UNIQUE (voucher_number);


--
-- Name: planning_applications planning_applications_application_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.planning_applications
    ADD CONSTRAINT planning_applications_application_number_unique UNIQUE (application_number);


--
-- Name: planning_applications planning_applications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.planning_applications
    ADD CONSTRAINT planning_applications_pkey PRIMARY KEY (id);


--
-- Name: pos_terminals pos_terminals_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pos_terminals
    ADD CONSTRAINT pos_terminals_pkey PRIMARY KEY (id);


--
-- Name: pos_terminals pos_terminals_terminal_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pos_terminals
    ADD CONSTRAINT pos_terminals_terminal_id_unique UNIQUE (terminal_id);


--
-- Name: program_budgets program_budgets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.program_budgets
    ADD CONSTRAINT program_budgets_pkey PRIMARY KEY (id);


--
-- Name: properties properties_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.properties
    ADD CONSTRAINT properties_pkey PRIMARY KEY (id);


--
-- Name: properties properties_property_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.properties
    ADD CONSTRAINT properties_property_number_unique UNIQUE (property_number);


--
-- Name: property_leases property_leases_lease_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_leases
    ADD CONSTRAINT property_leases_lease_number_unique UNIQUE (lease_number);


--
-- Name: property_leases property_leases_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_leases
    ADD CONSTRAINT property_leases_pkey PRIMARY KEY (id);


--
-- Name: property_rates property_rates_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_rates
    ADD CONSTRAINT property_rates_pkey PRIMARY KEY (id);


--
-- Name: property_valuations property_valuations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_valuations
    ADD CONSTRAINT property_valuations_pkey PRIMARY KEY (id);


--
-- Name: purchase_order_items purchase_order_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_order_items
    ADD CONSTRAINT purchase_order_items_pkey PRIMARY KEY (id);


--
-- Name: purchase_orders purchase_orders_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_pkey PRIMARY KEY (id);


--
-- Name: purchase_orders purchase_orders_po_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_po_number_unique UNIQUE (po_number);


--
-- Name: revenue_collections revenue_collections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.revenue_collections
    ADD CONSTRAINT revenue_collections_pkey PRIMARY KEY (id);


--
-- Name: revenue_collections revenue_collections_receipt_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.revenue_collections
    ADD CONSTRAINT revenue_collections_receipt_number_unique UNIQUE (receipt_number);


--
-- Name: service_request_attachments service_request_attachments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_request_attachments
    ADD CONSTRAINT service_request_attachments_pkey PRIMARY KEY (id);


--
-- Name: service_requests service_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests
    ADD CONSTRAINT service_requests_pkey PRIMARY KEY (id);


--
-- Name: service_requests service_requests_request_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests
    ADD CONSTRAINT service_requests_request_number_unique UNIQUE (request_number);


--
-- Name: service_types service_types_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_types
    ADD CONSTRAINT service_types_code_unique UNIQUE (code);


--
-- Name: service_types service_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_types
    ADD CONSTRAINT service_types_pkey PRIMARY KEY (id);


--
-- Name: stall_allocations stall_allocations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stall_allocations
    ADD CONSTRAINT stall_allocations_pkey PRIMARY KEY (id);


--
-- Name: suppliers suppliers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.suppliers
    ADD CONSTRAINT suppliers_pkey PRIMARY KEY (id);


--
-- Name: survey_projects survey_projects_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.survey_projects
    ADD CONSTRAINT survey_projects_pkey PRIMARY KEY (id);


--
-- Name: survey_projects survey_projects_project_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.survey_projects
    ADD CONSTRAINT survey_projects_project_number_unique UNIQUE (project_number);


--
-- Name: tenants tenants_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tenants
    ADD CONSTRAINT tenants_pkey PRIMARY KEY (id);


--
-- Name: tenants tenants_tenant_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tenants
    ADD CONSTRAINT tenants_tenant_number_unique UNIQUE (tenant_number);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_employee_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_employee_id_unique UNIQUE (employee_id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: utilities_connections utilities_connections_connection_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilities_connections
    ADD CONSTRAINT utilities_connections_connection_number_unique UNIQUE (connection_number);


--
-- Name: utilities_connections utilities_connections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilities_connections
    ADD CONSTRAINT utilities_connections_pkey PRIMARY KEY (id);


--
-- Name: voucher_lines voucher_lines_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.voucher_lines
    ADD CONSTRAINT voucher_lines_pkey PRIMARY KEY (id);


--
-- Name: vouchers vouchers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers
    ADD CONSTRAINT vouchers_pkey PRIMARY KEY (id);


--
-- Name: vouchers vouchers_voucher_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers
    ADD CONSTRAINT vouchers_voucher_number_unique UNIQUE (voucher_number);


--
-- Name: waste_collection_routes waste_collection_routes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.waste_collection_routes
    ADD CONSTRAINT waste_collection_routes_pkey PRIMARY KEY (id);


--
-- Name: waste_collection_routes waste_collection_routes_route_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.waste_collection_routes
    ADD CONSTRAINT waste_collection_routes_route_code_unique UNIQUE (route_code);


--
-- Name: water_bills water_bills_bill_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_bills
    ADD CONSTRAINT water_bills_bill_number_unique UNIQUE (bill_number);


--
-- Name: water_bills water_bills_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_bills
    ADD CONSTRAINT water_bills_pkey PRIMARY KEY (id);


--
-- Name: water_connections water_connections_connection_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections
    ADD CONSTRAINT water_connections_connection_number_unique UNIQUE (connection_number);


--
-- Name: water_connections water_connections_meter_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections
    ADD CONSTRAINT water_connections_meter_number_unique UNIQUE (meter_number);


--
-- Name: water_connections water_connections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections
    ADD CONSTRAINT water_connections_pkey PRIMARY KEY (id);


--
-- Name: water_meter_readings water_meter_readings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_meter_readings
    ADD CONSTRAINT water_meter_readings_pkey PRIMARY KEY (id);


--
-- Name: zimbabwe_chart_of_accounts zimbabwe_chart_of_accounts_account_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.zimbabwe_chart_of_accounts
    ADD CONSTRAINT zimbabwe_chart_of_accounts_account_code_unique UNIQUE (account_code);


--
-- Name: zimbabwe_chart_of_accounts zimbabwe_chart_of_accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.zimbabwe_chart_of_accounts
    ADD CONSTRAINT zimbabwe_chart_of_accounts_pkey PRIMARY KEY (id);


--
-- Name: asset_depreciation_history_fixed_asset_id_depreciation_year_dep; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX asset_depreciation_history_fixed_asset_id_depreciation_year_dep ON public.asset_depreciation_history USING btree (fixed_asset_id, depreciation_year, depreciation_month);


--
-- Name: bank_reconciliations_bank_account_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bank_reconciliations_bank_account_id_index ON public.bank_reconciliations USING btree (bank_account_id);


--
-- Name: bank_reconciliations_reconciliation_date_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bank_reconciliations_reconciliation_date_status_index ON public.bank_reconciliations USING btree (reconciliation_date, status);


--
-- Name: bank_statements_statement_date_bank_account_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bank_statements_statement_date_bank_account_id_index ON public.bank_statements USING btree (statement_date, bank_account_id);


--
-- Name: bill_line_items_bill_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_line_items_bill_id_index ON public.bill_line_items USING btree (bill_id);


--
-- Name: bill_line_items_service_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_line_items_service_id_index ON public.bill_line_items USING btree (service_id);


--
-- Name: bill_payments_bill_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_payments_bill_id_index ON public.bill_payments USING btree (bill_id);


--
-- Name: bill_payments_payment_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_payments_payment_date_index ON public.bill_payments USING btree (payment_date);


--
-- Name: bill_payments_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_payments_status_index ON public.bill_payments USING btree (status);


--
-- Name: bill_reminders_bill_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_reminders_bill_id_index ON public.bill_reminders USING btree (bill_id);


--
-- Name: bill_reminders_sent_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bill_reminders_sent_date_index ON public.bill_reminders USING btree (sent_date);


--
-- Name: cash_transactions_bank_account_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cash_transactions_bank_account_id_index ON public.cash_transactions USING btree (bank_account_id);


--
-- Name: cash_transactions_transaction_date_transaction_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cash_transactions_transaction_date_transaction_type_index ON public.cash_transactions USING btree (transaction_date, transaction_type);


--
-- Name: cashbook_entries_transaction_date_entry_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cashbook_entries_transaction_date_entry_type_index ON public.cashbook_entries USING btree (transaction_date, entry_type);


--
-- Name: customer_accounts_account_number_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX customer_accounts_account_number_index ON public.customer_accounts USING btree (account_number);


--
-- Name: customer_accounts_council_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX customer_accounts_council_id_index ON public.customer_accounts USING btree (council_id);


--
-- Name: customer_accounts_customer_name_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX customer_accounts_customer_name_index ON public.customer_accounts USING btree (customer_name);


--
-- Name: customer_accounts_is_active_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX customer_accounts_is_active_index ON public.customer_accounts USING btree (is_active);


--
-- Name: debtor_transactions_debtor_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX debtor_transactions_debtor_id_status_index ON public.debtor_transactions USING btree (debtor_id, status);


--
-- Name: debtor_transactions_due_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX debtor_transactions_due_date_index ON public.debtor_transactions USING btree (due_date);


--
-- Name: exchange_rate_histories_currency_code_effective_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX exchange_rate_histories_currency_code_effective_date_index ON public.exchange_rate_histories USING btree (currency_code, effective_date);


--
-- Name: fdms_receipts_fdms_transmitted_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fdms_receipts_fdms_transmitted_index ON public.fdms_receipts USING btree (fdms_transmitted);


--
-- Name: fdms_receipts_receipt_date_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fdms_receipts_receipt_date_status_index ON public.fdms_receipts USING btree (receipt_date, status);


--
-- Name: finance_general_journal_headers_journal_date_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX finance_general_journal_headers_journal_date_status_index ON public.finance_general_journal_headers USING btree (journal_date, status);


--
-- Name: finance_general_journal_headers_journal_number_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX finance_general_journal_headers_journal_number_index ON public.finance_general_journal_headers USING btree (journal_number);


--
-- Name: finance_general_journal_lines_journal_header_id_line_number_ind; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX finance_general_journal_lines_journal_header_id_line_number_ind ON public.finance_general_journal_lines USING btree (journal_header_id, line_number);


--
-- Name: financial_reports_report_type_report_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX financial_reports_report_type_report_date_index ON public.financial_reports USING btree (report_type, report_date);


--
-- Name: financial_reports_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX financial_reports_status_index ON public.financial_reports USING btree (status);


--
-- Name: fixed_assets_asset_category_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX fixed_assets_asset_category_status_index ON public.fixed_assets USING btree (asset_category, status);


--
-- Name: municipal_bills_council_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX municipal_bills_council_id_index ON public.municipal_bills USING btree (council_id);


--
-- Name: municipal_bills_customer_account_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX municipal_bills_customer_account_id_index ON public.municipal_bills USING btree (customer_account_id);


--
-- Name: municipal_bills_due_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX municipal_bills_due_date_index ON public.municipal_bills USING btree (due_date);


--
-- Name: municipal_bills_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX municipal_bills_status_index ON public.municipal_bills USING btree (status);


--
-- Name: municipal_service_categories_is_active_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX municipal_service_categories_is_active_index ON public.municipal_service_categories USING btree (is_active);


--
-- Name: payment_methods_code_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_methods_code_index ON public.payment_methods USING btree (code);


--
-- Name: payment_methods_is_active_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_methods_is_active_index ON public.payment_methods USING btree (is_active);


--
-- Name: payment_vouchers_payment_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_vouchers_payment_date_index ON public.payment_vouchers USING btree (payment_date);


--
-- Name: payment_vouchers_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_vouchers_status_index ON public.payment_vouchers USING btree (status);


--
-- Name: payment_vouchers_voucher_number_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payment_vouchers_voucher_number_index ON public.payment_vouchers USING btree (voucher_number);


--
-- Name: program_budgets_budget_year_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX program_budgets_budget_year_status_index ON public.program_budgets USING btree (budget_year, status);


--
-- Name: purchase_orders_po_date_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX purchase_orders_po_date_status_index ON public.purchase_orders USING btree (po_date, status);


--
-- Name: purchase_orders_supplier_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX purchase_orders_supplier_id_index ON public.purchase_orders USING btree (supplier_id);


--
-- Name: suppliers_name_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX suppliers_name_status_index ON public.suppliers USING btree (name, status);


--
-- Name: voucher_lines_voucher_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX voucher_lines_voucher_id_index ON public.voucher_lines USING btree (voucher_id);


--
-- Name: vouchers_voucher_date_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX vouchers_voucher_date_status_index ON public.vouchers USING btree (voucher_date, status);


--
-- Name: zimbabwe_chart_of_accounts_account_type_account_category_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX zimbabwe_chart_of_accounts_account_type_account_category_index ON public.zimbabwe_chart_of_accounts USING btree (account_type, account_category);


--
-- Name: zimbabwe_chart_of_accounts_parent_account_code_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX zimbabwe_chart_of_accounts_parent_account_code_index ON public.zimbabwe_chart_of_accounts USING btree (parent_account_code);


--
-- Name: ap_bills ap_bills_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills
    ADD CONSTRAINT ap_bills_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: ap_bills ap_bills_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills
    ADD CONSTRAINT ap_bills_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: ap_bills ap_bills_vendor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ap_bills
    ADD CONSTRAINT ap_bills_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES public.ap_vendors(id) ON DELETE CASCADE;


--
-- Name: ar_invoices ar_invoices_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_invoices
    ADD CONSTRAINT ar_invoices_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: ar_receipts ar_receipts_ar_invoice_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_ar_invoice_id_foreign FOREIGN KEY (ar_invoice_id) REFERENCES public.ar_invoices(id) ON DELETE SET NULL;


--
-- Name: ar_receipts ar_receipts_bank_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_bank_account_id_foreign FOREIGN KEY (bank_account_id) REFERENCES public.bank_accounts(id) ON DELETE SET NULL;


--
-- Name: ar_receipts ar_receipts_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE SET NULL;


--
-- Name: ar_receipts ar_receipts_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: ar_receipts ar_receipts_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: ar_receipts ar_receipts_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE SET NULL;


--
-- Name: ar_receipts ar_receipts_office_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ar_receipts
    ADD CONSTRAINT ar_receipts_office_id_foreign FOREIGN KEY (office_id) REFERENCES public.offices(id) ON DELETE SET NULL;


--
-- Name: asset_depreciation_history asset_depreciation_history_fixed_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asset_depreciation_history
    ADD CONSTRAINT asset_depreciation_history_fixed_asset_id_foreign FOREIGN KEY (fixed_asset_id) REFERENCES public.fixed_assets(id) ON DELETE CASCADE;


--
-- Name: bank_reconciliations bank_reconciliations_bank_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_bank_account_id_foreign FOREIGN KEY (bank_account_id) REFERENCES public.bank_accounts(id);


--
-- Name: bank_reconciliations bank_reconciliations_bank_statement_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_bank_statement_id_foreign FOREIGN KEY (bank_statement_id) REFERENCES public.bank_statements(id);


--
-- Name: bank_reconciliations bank_reconciliations_prepared_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_prepared_by_foreign FOREIGN KEY (prepared_by) REFERENCES public.users(id);


--
-- Name: bank_reconciliations bank_reconciliations_reviewed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_reconciliations
    ADD CONSTRAINT bank_reconciliations_reviewed_by_foreign FOREIGN KEY (reviewed_by) REFERENCES public.users(id);


--
-- Name: bank_statements bank_statements_bank_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bank_statements
    ADD CONSTRAINT bank_statements_bank_account_id_foreign FOREIGN KEY (bank_account_id) REFERENCES public.bank_accounts(id);


--
-- Name: bill_line_items bill_line_items_bill_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_line_items
    ADD CONSTRAINT bill_line_items_bill_id_foreign FOREIGN KEY (bill_id) REFERENCES public.municipal_bills(id) ON DELETE CASCADE;


--
-- Name: bill_line_items bill_line_items_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_line_items
    ADD CONSTRAINT bill_line_items_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.municipal_services(id) ON DELETE CASCADE;


--
-- Name: bill_payments bill_payments_bill_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_payments
    ADD CONSTRAINT bill_payments_bill_id_foreign FOREIGN KEY (bill_id) REFERENCES public.municipal_bills(id) ON DELETE CASCADE;


--
-- Name: bill_payments bill_payments_payment_method_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_payments
    ADD CONSTRAINT bill_payments_payment_method_id_foreign FOREIGN KEY (payment_method_id) REFERENCES public.payment_methods(id) ON DELETE SET NULL;


--
-- Name: bill_reminders bill_reminders_bill_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bill_reminders
    ADD CONSTRAINT bill_reminders_bill_id_foreign FOREIGN KEY (bill_id) REFERENCES public.municipal_bills(id) ON DELETE CASCADE;


--
-- Name: budgets budgets_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.budgets
    ADD CONSTRAINT budgets_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: building_inspections building_inspections_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.building_inspections
    ADD CONSTRAINT building_inspections_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: burial_records burial_records_plot_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.burial_records
    ADD CONSTRAINT burial_records_plot_id_foreign FOREIGN KEY (plot_id) REFERENCES public.cemetery_plots(id) ON DELETE CASCADE;


--
-- Name: cash_transactions cash_transactions_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions
    ADD CONSTRAINT cash_transactions_account_id_foreign FOREIGN KEY (account_id) REFERENCES public.finance_chart_of_accounts(id);


--
-- Name: cash_transactions cash_transactions_bank_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions
    ADD CONSTRAINT cash_transactions_bank_account_id_foreign FOREIGN KEY (bank_account_id) REFERENCES public.bank_accounts(id);


--
-- Name: cash_transactions cash_transactions_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions
    ADD CONSTRAINT cash_transactions_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: cash_transactions cash_transactions_reconciliation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cash_transactions
    ADD CONSTRAINT cash_transactions_reconciliation_id_foreign FOREIGN KEY (reconciliation_id) REFERENCES public.bank_reconciliations(id);


--
-- Name: cashbook_entries cashbook_entries_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cashbook_entries
    ADD CONSTRAINT cashbook_entries_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: cashbook_entries cashbook_entries_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cashbook_entries
    ADD CONSTRAINT cashbook_entries_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: cemetery_plots cemetery_plots_cemetery_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cemetery_plots
    ADD CONSTRAINT cemetery_plots_cemetery_id_foreign FOREIGN KEY (cemetery_id) REFERENCES public.cemeteries(id) ON DELETE CASCADE;


--
-- Name: committee_meetings committee_meetings_committee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.committee_meetings
    ADD CONSTRAINT committee_meetings_committee_id_foreign FOREIGN KEY (committee_id) REFERENCES public.committee_committees(id) ON DELETE CASCADE;


--
-- Name: cost_centers cost_centers_manager_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cost_centers
    ADD CONSTRAINT cost_centers_manager_id_foreign FOREIGN KEY (manager_id) REFERENCES public.users(id);


--
-- Name: customer_accounts customer_accounts_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customer_accounts
    ADD CONSTRAINT customer_accounts_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE CASCADE;


--
-- Name: debtor_transactions debtor_transactions_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtor_transactions
    ADD CONSTRAINT debtor_transactions_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: debtor_transactions debtor_transactions_debtor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.debtor_transactions
    ADD CONSTRAINT debtor_transactions_debtor_id_foreign FOREIGN KEY (debtor_id) REFERENCES public.debtors(id) ON DELETE CASCADE;


--
-- Name: departments departments_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE CASCADE;


--
-- Name: exchange_rate_histories exchange_rate_histories_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.exchange_rate_histories
    ADD CONSTRAINT exchange_rate_histories_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: exchange_rate_histories exchange_rate_histories_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.exchange_rate_histories
    ADD CONSTRAINT exchange_rate_histories_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: facility_bookings facility_bookings_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facility_bookings
    ADD CONSTRAINT facility_bookings_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: facility_bookings facility_bookings_facility_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facility_bookings
    ADD CONSTRAINT facility_bookings_facility_id_foreign FOREIGN KEY (facility_id) REFERENCES public.facilities(id) ON DELETE CASCADE;


--
-- Name: fdms_receipts fdms_receipts_cashier_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_cashier_id_foreign FOREIGN KEY (cashier_id) REFERENCES public.users(id);


--
-- Name: fdms_receipts fdms_receipts_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: fdms_receipts fdms_receipts_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE SET NULL;


--
-- Name: fdms_receipts fdms_receipts_original_receipt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fdms_receipts
    ADD CONSTRAINT fdms_receipts_original_receipt_id_foreign FOREIGN KEY (original_receipt_id) REFERENCES public.fdms_receipts(id) ON DELETE SET NULL;


--
-- Name: finance_budgets finance_budgets_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_budgets
    ADD CONSTRAINT finance_budgets_account_id_foreign FOREIGN KEY (account_id) REFERENCES public.finance_chart_of_accounts(id) ON DELETE CASCADE;


--
-- Name: finance_general_journal_headers finance_general_journal_headers_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers
    ADD CONSTRAINT finance_general_journal_headers_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id);


--
-- Name: finance_general_journal_headers finance_general_journal_headers_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers
    ADD CONSTRAINT finance_general_journal_headers_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: finance_general_journal_headers finance_general_journal_headers_posted_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_headers
    ADD CONSTRAINT finance_general_journal_headers_posted_by_foreign FOREIGN KEY (posted_by) REFERENCES public.users(id);


--
-- Name: finance_general_journal_lines finance_general_journal_lines_account_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_lines
    ADD CONSTRAINT finance_general_journal_lines_account_code_foreign FOREIGN KEY (account_code) REFERENCES public.finance_chart_of_accounts(account_code);


--
-- Name: finance_general_journal_lines finance_general_journal_lines_journal_header_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_journal_lines
    ADD CONSTRAINT finance_general_journal_lines_journal_header_id_foreign FOREIGN KEY (journal_header_id) REFERENCES public.finance_general_journal_headers(id) ON DELETE CASCADE;


--
-- Name: finance_general_ledger finance_general_ledger_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_ledger
    ADD CONSTRAINT finance_general_ledger_account_id_foreign FOREIGN KEY (account_id) REFERENCES public.finance_chart_of_accounts(id) ON DELETE CASCADE;


--
-- Name: finance_general_ledger finance_general_ledger_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_general_ledger
    ADD CONSTRAINT finance_general_ledger_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: finance_invoice_items finance_invoice_items_invoice_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoice_items
    ADD CONSTRAINT finance_invoice_items_invoice_id_foreign FOREIGN KEY (invoice_id) REFERENCES public.finance_invoices(id) ON DELETE CASCADE;


--
-- Name: finance_invoices finance_invoices_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_invoices
    ADD CONSTRAINT finance_invoices_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: finance_payments finance_payments_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments
    ADD CONSTRAINT finance_payments_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: finance_payments finance_payments_invoice_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments
    ADD CONSTRAINT finance_payments_invoice_id_foreign FOREIGN KEY (invoice_id) REFERENCES public.finance_invoices(id);


--
-- Name: finance_payments finance_payments_processed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.finance_payments
    ADD CONSTRAINT finance_payments_processed_by_foreign FOREIGN KEY (processed_by) REFERENCES public.users(id);


--
-- Name: financial_reports financial_reports_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.financial_reports
    ADD CONSTRAINT financial_reports_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: fixed_assets fixed_assets_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fixed_assets
    ADD CONSTRAINT fixed_assets_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: fleet_vehicles fleet_vehicles_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fleet_vehicles
    ADD CONSTRAINT fleet_vehicles_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE CASCADE;


--
-- Name: gate_takings gate_takings_facility_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gate_takings
    ADD CONSTRAINT gate_takings_facility_id_foreign FOREIGN KEY (facility_id) REFERENCES public.facilities(id) ON DELETE CASCADE;


--
-- Name: health_inspections health_inspections_facility_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.health_inspections
    ADD CONSTRAINT health_inspections_facility_id_foreign FOREIGN KEY (facility_id) REFERENCES public.health_facilities(id);


--
-- Name: housing_allocations housing_allocations_application_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_allocations
    ADD CONSTRAINT housing_allocations_application_id_foreign FOREIGN KEY (application_id) REFERENCES public.housing_applications(id) ON DELETE CASCADE;


--
-- Name: housing_allocations housing_allocations_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_allocations
    ADD CONSTRAINT housing_allocations_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.housing_properties(id) ON DELETE CASCADE;


--
-- Name: housing_waiting_list housing_waiting_list_application_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.housing_waiting_list
    ADD CONSTRAINT housing_waiting_list_application_id_foreign FOREIGN KEY (application_id) REFERENCES public.housing_applications(id) ON DELETE CASCADE;


--
-- Name: hr_attendance hr_attendance_employee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_attendance
    ADD CONSTRAINT hr_attendance_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES public.hr_employees(id) ON DELETE CASCADE;


--
-- Name: hr_employees hr_employees_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_employees
    ADD CONSTRAINT hr_employees_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE CASCADE;


--
-- Name: hr_payroll hr_payroll_employee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hr_payroll
    ADD CONSTRAINT hr_payroll_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES public.hr_employees(id) ON DELETE CASCADE;


--
-- Name: inventory_items inventory_items_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_items
    ADD CONSTRAINT inventory_items_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.inventory_categories(id) ON DELETE CASCADE;


--
-- Name: inventory_transactions inventory_transactions_item_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_transactions
    ADD CONSTRAINT inventory_transactions_item_id_foreign FOREIGN KEY (item_id) REFERENCES public.inventory_items(id) ON DELETE CASCADE;


--
-- Name: inventory_transactions inventory_transactions_processed_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_transactions
    ADD CONSTRAINT inventory_transactions_processed_by_foreign FOREIGN KEY (processed_by) REFERENCES public.users(id);


--
-- Name: market_stalls market_stalls_market_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.market_stalls
    ADD CONSTRAINT market_stalls_market_id_foreign FOREIGN KEY (market_id) REFERENCES public.markets(id) ON DELETE CASCADE;


--
-- Name: municipal_bills municipal_bills_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_bills
    ADD CONSTRAINT municipal_bills_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE CASCADE;


--
-- Name: municipal_bills municipal_bills_customer_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_bills
    ADD CONSTRAINT municipal_bills_customer_account_id_foreign FOREIGN KEY (customer_account_id) REFERENCES public.customer_accounts(id) ON DELETE CASCADE;


--
-- Name: municipal_services municipal_services_service_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.municipal_services
    ADD CONSTRAINT municipal_services_service_type_id_foreign FOREIGN KEY (service_type_id) REFERENCES public.service_types(id) ON DELETE CASCADE;


--
-- Name: offices offices_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offices
    ADD CONSTRAINT offices_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE CASCADE;


--
-- Name: offices offices_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offices
    ADD CONSTRAINT offices_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE SET NULL;


--
-- Name: parking_violations parking_violations_zone_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.parking_violations
    ADD CONSTRAINT parking_violations_zone_id_foreign FOREIGN KEY (zone_id) REFERENCES public.parking_zones(id) ON DELETE CASCADE;


--
-- Name: payment_vouchers payment_vouchers_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: payment_vouchers payment_vouchers_bank_account_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_bank_account_id_foreign FOREIGN KEY (bank_account_id) REFERENCES public.bank_accounts(id) ON DELETE SET NULL;


--
-- Name: payment_vouchers payment_vouchers_bill_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_bill_id_foreign FOREIGN KEY (bill_id) REFERENCES public.ap_bills(id) ON DELETE SET NULL;


--
-- Name: payment_vouchers payment_vouchers_requested_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_requested_by_foreign FOREIGN KEY (requested_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: payment_vouchers payment_vouchers_vendor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_vouchers
    ADD CONSTRAINT payment_vouchers_vendor_id_foreign FOREIGN KEY (vendor_id) REFERENCES public.ap_vendors(id) ON DELETE SET NULL;


--
-- Name: planning_applications planning_applications_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.planning_applications
    ADD CONSTRAINT planning_applications_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: pos_terminals pos_terminals_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pos_terminals
    ADD CONSTRAINT pos_terminals_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: program_budgets program_budgets_responsible_officer_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.program_budgets
    ADD CONSTRAINT program_budgets_responsible_officer_foreign FOREIGN KEY (responsible_officer) REFERENCES public.users(id);


--
-- Name: property_leases property_leases_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_leases
    ADD CONSTRAINT property_leases_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: property_rates property_rates_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_rates
    ADD CONSTRAINT property_rates_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: property_valuations property_valuations_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.property_valuations
    ADD CONSTRAINT property_valuations_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: purchase_order_items purchase_order_items_item_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_order_items
    ADD CONSTRAINT purchase_order_items_item_id_foreign FOREIGN KEY (item_id) REFERENCES public.inventory_items(id);


--
-- Name: purchase_order_items purchase_order_items_purchase_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_order_items
    ADD CONSTRAINT purchase_order_items_purchase_order_id_foreign FOREIGN KEY (purchase_order_id) REFERENCES public.purchase_orders(id) ON DELETE CASCADE;


--
-- Name: purchase_orders purchase_orders_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id);


--
-- Name: purchase_orders purchase_orders_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id);


--
-- Name: purchase_orders purchase_orders_supplier_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.purchase_orders
    ADD CONSTRAINT purchase_orders_supplier_id_foreign FOREIGN KEY (supplier_id) REFERENCES public.suppliers(id);


--
-- Name: revenue_collections revenue_collections_collected_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.revenue_collections
    ADD CONSTRAINT revenue_collections_collected_by_foreign FOREIGN KEY (collected_by) REFERENCES public.users(id);


--
-- Name: revenue_collections revenue_collections_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.revenue_collections
    ADD CONSTRAINT revenue_collections_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: service_request_attachments service_request_attachments_service_request_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_request_attachments
    ADD CONSTRAINT service_request_attachments_service_request_id_foreign FOREIGN KEY (service_request_id) REFERENCES public.service_requests(id) ON DELETE CASCADE;


--
-- Name: service_requests service_requests_assigned_to_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests
    ADD CONSTRAINT service_requests_assigned_to_foreign FOREIGN KEY (assigned_to) REFERENCES public.users(id);


--
-- Name: service_requests service_requests_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests
    ADD CONSTRAINT service_requests_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: service_requests service_requests_service_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.service_requests
    ADD CONSTRAINT service_requests_service_type_id_foreign FOREIGN KEY (service_type_id) REFERENCES public.service_types(id) ON DELETE CASCADE;


--
-- Name: stall_allocations stall_allocations_stall_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stall_allocations
    ADD CONSTRAINT stall_allocations_stall_id_foreign FOREIGN KEY (stall_id) REFERENCES public.market_stalls(id) ON DELETE CASCADE;


--
-- Name: tenants tenants_allocation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tenants
    ADD CONSTRAINT tenants_allocation_id_foreign FOREIGN KEY (allocation_id) REFERENCES public.housing_allocations(id) ON DELETE CASCADE;


--
-- Name: users users_council_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_council_id_foreign FOREIGN KEY (council_id) REFERENCES public.councils(id) ON DELETE CASCADE;


--
-- Name: users users_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE SET NULL;


--
-- Name: users users_office_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_office_id_foreign FOREIGN KEY (office_id) REFERENCES public.offices(id) ON DELETE SET NULL;


--
-- Name: utilities_connections utilities_connections_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilities_connections
    ADD CONSTRAINT utilities_connections_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: utilities_connections utilities_connections_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.utilities_connections
    ADD CONSTRAINT utilities_connections_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: voucher_lines voucher_lines_voucher_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.voucher_lines
    ADD CONSTRAINT voucher_lines_voucher_id_foreign FOREIGN KEY (voucher_id) REFERENCES public.vouchers(id) ON DELETE CASCADE;


--
-- Name: vouchers vouchers_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers
    ADD CONSTRAINT vouchers_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: vouchers vouchers_currency_code_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers
    ADD CONSTRAINT vouchers_currency_code_foreign FOREIGN KEY (currency_code) REFERENCES public.currencies(currency_code);


--
-- Name: vouchers vouchers_prepared_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vouchers
    ADD CONSTRAINT vouchers_prepared_by_foreign FOREIGN KEY (prepared_by) REFERENCES public.users(id);


--
-- Name: waste_collection_routes waste_collection_routes_assigned_vehicle_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.waste_collection_routes
    ADD CONSTRAINT waste_collection_routes_assigned_vehicle_id_foreign FOREIGN KEY (assigned_vehicle_id) REFERENCES public.fleet_vehicles(id);


--
-- Name: water_bills water_bills_connection_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_bills
    ADD CONSTRAINT water_bills_connection_id_foreign FOREIGN KEY (connection_id) REFERENCES public.water_connections(id) ON DELETE CASCADE;


--
-- Name: water_connections water_connections_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections
    ADD CONSTRAINT water_connections_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE CASCADE;


--
-- Name: water_connections water_connections_property_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_connections
    ADD CONSTRAINT water_connections_property_id_foreign FOREIGN KEY (property_id) REFERENCES public.properties(id) ON DELETE CASCADE;


--
-- Name: water_meter_readings water_meter_readings_connection_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.water_meter_readings
    ADD CONSTRAINT water_meter_readings_connection_id_foreign FOREIGN KEY (connection_id) REFERENCES public.water_connections(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.9
-- Dumped by pg_dump version 16.9

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
361	2024_01_01_000008_create_municipal_services_table	1
362	2024_01_01_000009_create_service_requests_table	1
363	2024_01_01_000010_create_service_request_attachments_table	1
364	2024_01_01_000011_create_housing_properties_table	1
365	2024_01_01_000012_create_housing_applications_table	1
366	2024_01_01_000013_create_housing_waiting_list_table	1
367	2024_01_01_000014_create_housing_allocations_table	1
368	2024_01_01_000015_create_tenants_table	1
369	2024_01_01_000016_create_facilities_table	1
370	2024_01_01_000017_create_facility_bookings_table	1
371	2024_01_01_000018_create_gate_takings_table	1
372	2024_01_01_000019_create_cemeteries_table	1
373	2024_01_01_000020_create_cemetery_plots_table	1
374	2024_01_01_000021_create_burial_records_table	1
375	2024_01_01_000022_create_properties_table	1
376	2024_01_01_000023_create_property_valuations_table	1
377	2024_01_01_000024_create_property_leases_table	1
378	2024_01_01_000025_create_water_connections_table	1
379	2024_01_01_000026_create_water_meter_readings_table	1
380	2024_01_01_000027_create_water_bills_table	1
381	2024_01_01_000028_create_planning_applications_table	1
382	2024_01_01_000029_create_finance_chart_of_accounts_table	1
383	2024_01_01_000030_create_finance_general_ledger_table	1
384	2024_01_01_000031_create_finance_budgets_table	1
385	2024_01_01_000032_create_finance_invoices_table	1
386	2024_01_01_000033_create_finance_invoice_items_table	1
387	2024_01_01_000034_create_finance_payments_table	1
388	2024_01_01_000035_create_inventory_categories_table	1
389	2024_01_01_000036_create_inventory_items_table	1
390	2024_01_01_000037_create_inventory_transactions_table	1
391	2024_01_01_000038_create_hr_employees_table	1
392	2024_01_01_000039_create_hr_attendance_table	1
393	2024_01_01_000040_create_hr_payroll_table	1
394	2024_01_01_000041_create_committee_committees_table	1
395	2024_01_01_000042_create_committee_meetings_table	1
396	2024_01_01_000043_create_health_facilities_table	1
397	2024_01_01_000044_create_health_inspections_table	1
398	2024_01_01_000045_create_licensing_business_licenses_table	1
399	2024_01_01_000046_create_parking_zones_table	1
400	2024_01_01_000047_create_parking_violations_table	1
401	2024_01_01_000048_create_markets_table	1
402	2024_01_01_000049_create_market_stalls_table	1
403	2024_01_01_000050_create_stall_allocations_table	1
404	2024_01_01_000051_create_survey_projects_table	1
405	2024_01_01_000052_create_engineering_projects_table	1
406	2024_01_01_000053_create_infrastructure_assets_table	1
407	2024_01_01_000054_create_building_inspections_table	1
408	2024_01_01_000055_create_utilities_connections_table	1
409	2024_01_01_000056_create_event_permits_table	1
410	2024_01_01_000057_create_revenue_collections_table	1
411	2024_01_01_000058_create_property_rates_table	1
412	2024_01_01_000059_create_fleet_vehicles_table	1
413	2024_01_01_000060_create_waste_collection_routes_table	1
414	2025_01_17_000001_create_cost_centers_table	1
415	2025_01_17_000002_create_financial_reports_table	1
416	2025_01_18_000001_create_enhanced_finance_tables	1
354	2024_01_01_000001_create_councils_table	1
355	2024_01_01_000002_create_departments_table	1
356	2024_01_01_000003_create_offices_table	1
357	2024_01_01_000004_create_users_table	1
358	2024_01_01_000005_create_customers_table	1
359	2024_01_01_000006_create_service_types_table	1
360	2024_01_01_000007_create_communications_table	1
417	2025_01_18_000003_create_fdms_receipts_table	2
418	2025_01_18_000004_create_pos_terminals_table	2
419	2025_01_18_000005_create_asset_categories_table	2
420	2025_01_18_000006_create_asset_locations_table	2
421	2025_01_18_000005_create_suppliers_table	3
422	2025_01_18_000006_create_purchase_orders_table	3
423	2025_01_18_000007_create_purchase_order_items_table	3
424	2025_01_18_000008_create_enhanced_finance_tables	4
425	2025_08_18_000001_create_general_journal_headers_table	4
426	2025_08_18_000002_create_general_journal_lines_table	4
427	2025_08_18_140043_add_soft_deletes_to_finance_chart_of_accounts_table	4
428	2025_08_18_140434_create_general_ledger_table	4
429	2025_08_18_140435_create_bank_accounts_table	4
430	2025_08_18_140436_create_bank_statements_table	4
431	2025_08_18_140437_create_bank_reconciliations_table	4
432	2025_08_18_140438_create_cash_transactions_table	4
433	2025_08_18_140706_create_ar_invoices_table	4
434	2025_08_18_140707_create_ar_receipts_table	4
435	2025_08_18_140843_create_ap_vendors_table	4
436	2025_08_18_140844_create_ap_bills_table	4
437	2025_08_18_141116_create_budgets_table	4
438	2025_08_18_141117_create_payment_vouchers_table	4
439	2025_08_18_141121_create_payment_methods_table	4
440	2025_08_18_141122_create_customer_accounts_table	4
441	2025_08_18_141123_create_municipal_bills_table	4
442	2025_08_18_141124_create_municipal_service_categories_table	4
443	2025_08_18_141125_create_bill_line_items_table	4
444	2025_08_18_141126_create_bill_payments_table	4
445	2025_08_18_141127_create_bill_reminders_table	4
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 445, true);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.9
-- Dumped by pg_dump version 16.9

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
361	2024_01_01_000008_create_municipal_services_table	1
362	2024_01_01_000009_create_service_requests_table	1
363	2024_01_01_000010_create_service_request_attachments_table	1
364	2024_01_01_000011_create_housing_properties_table	1
365	2024_01_01_000012_create_housing_applications_table	1
366	2024_01_01_000013_create_housing_waiting_list_table	1
367	2024_01_01_000014_create_housing_allocations_table	1
368	2024_01_01_000015_create_tenants_table	1
369	2024_01_01_000016_create_facilities_table	1
370	2024_01_01_000017_create_facility_bookings_table	1
371	2024_01_01_000018_create_gate_takings_table	1
372	2024_01_01_000019_create_cemeteries_table	1
373	2024_01_01_000020_create_cemetery_plots_table	1
374	2024_01_01_000021_create_burial_records_table	1
375	2024_01_01_000022_create_properties_table	1
376	2024_01_01_000023_create_property_valuations_table	1
377	2024_01_01_000024_create_property_leases_table	1
378	2024_01_01_000025_create_water_connections_table	1
379	2024_01_01_000026_create_water_meter_readings_table	1
380	2024_01_01_000027_create_water_bills_table	1
381	2024_01_01_000028_create_planning_applications_table	1
382	2024_01_01_000029_create_finance_chart_of_accounts_table	1
383	2024_01_01_000030_create_finance_general_ledger_table	1
384	2024_01_01_000031_create_finance_budgets_table	1
385	2024_01_01_000032_create_finance_invoices_table	1
386	2024_01_01_000033_create_finance_invoice_items_table	1
387	2024_01_01_000034_create_finance_payments_table	1
388	2024_01_01_000035_create_inventory_categories_table	1
389	2024_01_01_000036_create_inventory_items_table	1
390	2024_01_01_000037_create_inventory_transactions_table	1
391	2024_01_01_000038_create_hr_employees_table	1
392	2024_01_01_000039_create_hr_attendance_table	1
393	2024_01_01_000040_create_hr_payroll_table	1
394	2024_01_01_000041_create_committee_committees_table	1
395	2024_01_01_000042_create_committee_meetings_table	1
396	2024_01_01_000043_create_health_facilities_table	1
397	2024_01_01_000044_create_health_inspections_table	1
398	2024_01_01_000045_create_licensing_business_licenses_table	1
399	2024_01_01_000046_create_parking_zones_table	1
400	2024_01_01_000047_create_parking_violations_table	1
401	2024_01_01_000048_create_markets_table	1
402	2024_01_01_000049_create_market_stalls_table	1
403	2024_01_01_000050_create_stall_allocations_table	1
404	2024_01_01_000051_create_survey_projects_table	1
405	2024_01_01_000052_create_engineering_projects_table	1
406	2024_01_01_000053_create_infrastructure_assets_table	1
407	2024_01_01_000054_create_building_inspections_table	1
408	2024_01_01_000055_create_utilities_connections_table	1
409	2024_01_01_000056_create_event_permits_table	1
410	2024_01_01_000057_create_revenue_collections_table	1
411	2024_01_01_000058_create_property_rates_table	1
412	2024_01_01_000059_create_fleet_vehicles_table	1
413	2024_01_01_000060_create_waste_collection_routes_table	1
414	2025_01_17_000001_create_cost_centers_table	1
415	2025_01_17_000002_create_financial_reports_table	1
416	2025_01_18_000001_create_enhanced_finance_tables	1
354	2024_01_01_000001_create_councils_table	1
355	2024_01_01_000002_create_departments_table	1
356	2024_01_01_000003_create_offices_table	1
357	2024_01_01_000004_create_users_table	1
358	2024_01_01_000005_create_customers_table	1
359	2024_01_01_000006_create_service_types_table	1
360	2024_01_01_000007_create_communications_table	1
417	2025_01_18_000003_create_fdms_receipts_table	2
418	2025_01_18_000004_create_pos_terminals_table	2
419	2025_01_18_000005_create_asset_categories_table	2
420	2025_01_18_000006_create_asset_locations_table	2
421	2025_01_18_000005_create_suppliers_table	3
422	2025_01_18_000006_create_purchase_orders_table	3
423	2025_01_18_000007_create_purchase_order_items_table	3
424	2025_01_18_000008_create_enhanced_finance_tables	4
425	2025_08_18_000001_create_general_journal_headers_table	4
426	2025_08_18_000002_create_general_journal_lines_table	4
427	2025_08_18_140043_add_soft_deletes_to_finance_chart_of_accounts_table	4
428	2025_08_18_140434_create_general_ledger_table	4
429	2025_08_18_140435_create_bank_accounts_table	4
430	2025_08_18_140436_create_bank_statements_table	4
431	2025_08_18_140437_create_bank_reconciliations_table	4
432	2025_08_18_140438_create_cash_transactions_table	4
433	2025_08_18_140706_create_ar_invoices_table	4
434	2025_08_18_140707_create_ar_receipts_table	4
435	2025_08_18_140843_create_ap_vendors_table	4
436	2025_08_18_140844_create_ap_bills_table	4
437	2025_08_18_141116_create_budgets_table	4
438	2025_08_18_141117_create_payment_vouchers_table	4
439	2025_08_18_141121_create_payment_methods_table	4
440	2025_08_18_141122_create_customer_accounts_table	4
441	2025_08_18_141123_create_municipal_bills_table	4
442	2025_08_18_141124_create_municipal_service_categories_table	4
443	2025_08_18_141125_create_bill_line_items_table	4
444	2025_08_18_141126_create_bill_payments_table	4
445	2025_08_18_141127_create_bill_reminders_table	4
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 445, true);


--
-- PostgreSQL database dump complete
--

