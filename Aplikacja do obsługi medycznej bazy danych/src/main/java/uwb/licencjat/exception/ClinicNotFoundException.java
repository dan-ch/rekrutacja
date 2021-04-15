package uwb.licencjat.exception;

public class ClinicNotFoundException extends RuntimeException{
    public ClinicNotFoundException() {
        super("Clinic not found");
    }

    public ClinicNotFoundException(String message) {
        super(message);
    }
}
