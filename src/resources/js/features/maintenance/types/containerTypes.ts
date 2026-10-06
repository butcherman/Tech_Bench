type ContainerStatus =
    | "created"
    | "restarting"
    | "running"
    | "removing"
    | "paused"
    | "exited"
    | "dead";

type ContainerHealth = "healthy" | "unhealthy" | "starting";

interface ContainerSummary {
    service: string;
    name: string;
    description: string;
    restartable: boolean;
    status: ContainerStatus;
    running: boolean;
    health?: ContainerHealth;
    started_at: string;
}
