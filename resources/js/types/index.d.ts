export interface User {
    id: number
    name: string
    username: string
    phone: string
    email: string
    profile_photo_url: string
    email_verified_at: string
    created_at: string
    updated_at: string
}

export interface Role {
    id: number
    name: string
    title: string
    description: string
    guard_name: string
    created_at: string
    updated_at: string
}

export interface Data<T> {
    current_page: number
    data: T[]
    first_page_url: string
    from: number
    last_page: number
    last_page_url: string
    links: { active: boolean, label: string, url: string | null }[]
    next_page_url: string | null
    path: string
    per_page: number
    prev_page_url: string | null
    to: number
    total: number
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User
    }
    flash?: {
        status: 'success' | 'error' | 'warning'
        message: string
    }
}
