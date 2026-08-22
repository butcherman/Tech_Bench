export class ValidationError extends Error {
    constructor(
        public readonly errors: ValidationError,
        message: string,
    ) {
        super(message);
        this.name = "LaravelValidationError";
    }
}
