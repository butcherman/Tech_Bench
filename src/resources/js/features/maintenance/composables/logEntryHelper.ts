export const useLogEntryHelper = () => {
    const getBadgeClass = (logLevel: LogLevel): string => {
        return {
            emergency: "bg-red-100 text-red-900 ring-1 ring-inset ring-red-400",
            alert: "bg-red-100 text-red-800 ring-1 ring-inset ring-red-300",
            critical: "bg-red-50 text-red-800 ring-1 ring-inset ring-red-300",
            error: "bg-red-50 text-red-700 ring-1 ring-inset ring-red-200",
            warning:
                "bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200",
            notice: "bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-200",
            info: "bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200",
            debug: "bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-200",
        }[logLevel];
    };

    const getBadgeIcon = (logLevel: LogLevel): string => {
        return {
            emergency: "ambulance",
            alert: "bullhorn",
            critical: "heartbeat",
            error: "times-circle",
            warning: "exclamation-triangle",
            notice: "exclamation-circle",
            info: "info",
            debug: "bug",
        }[logLevel];
    };

    return {
        getBadgeClass,
        getBadgeIcon,
    };
};
