type user = {
    username: string;
    email: string;
    first_name: string;
    last_name: string;
    full_name: string;
    initials: string;
};

type userNotificationProp = {
    list: userNotification[];
    new: number;
};

type userNotification = {
    created_at: string;
    id: string;
    read_at: string | null;
    data: {
        component: string;
        subject: string;
        props: object;
    };
};
