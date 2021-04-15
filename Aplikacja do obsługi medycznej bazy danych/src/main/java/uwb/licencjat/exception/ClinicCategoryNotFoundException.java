package uwb.licencjat.exception;

import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.ResponseStatus;

import java.util.function.Supplier;


public class ClinicCategoryNotFoundException extends RuntimeException{
    public ClinicCategoryNotFoundException(String message) {
        super(message);
    }

    public ClinicCategoryNotFoundException() {
        super("Clinic category not found");
    }
}
